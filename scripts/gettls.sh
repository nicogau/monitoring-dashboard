#!/bin/bash

if [ "$#" -ne 1 ]; then
  exit 1
fi

domain="$1"
has_cert=false

# one liner to get certificate expirency date field
# openssl s_client -showcerts -connect "$server":443 2>/dev/null <<< "Q" | openssl x509 -inform pem -noout -text | awk 'BEGIN {FS=": "} /Not After/{print $2 }'

# get tls certificate
# echo "Q" used to stop connection
cert=$(openssl s_client -showcerts -connect "$domain":443 2>/dev/null <<< "Q")

if [ -n "$cert" ]; then
    # expirency date of a certificate for a given server
    exp_date=$(echo "$cert" | openssl x509 -noout -enddate 2>/dev/null | cut -d "=" -f 2)
    # issuer of the certificate
    issuer=$(echo "$cert" | openssl x509 -noout -issuer 2>/dev/null )
else
    issuer="undefined"
fi


# calculate the number of days left to expirency
if [ -n "$exp_date" ]; then
    diff_days=$((($(date -d "$exp_date" '+%s') - $(date  '+%s'))/86400))
    has_cert=true
else
    exp_date='undefined'
    diff_days='undefined'
fi

echo "{\"domain\":\"$domain\",\"issuer\":\"$issuer\",\"cert\":\"$has_cert\",\"exp\":\"$exp_date\",\"days_left\":\"$diff_days\"}"
