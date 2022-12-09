#! /usr/bin/env bash
if [ "$#" -ne 1 ]; then
  exit 1
fi

domain="$1"
has_cert=false

# escape quotes in certificate
# some quotes can appear in the issuer field of the TLS certificate
rm_quotes() {
  if [ "$#" -ne 1 ]; then
    exit 1
  fi
  echo "$1" | tr -d '"'
}

# one liner to get certificate expirency date field
# openssl s_client -showcerts -connect "$server":443 2>/dev/null <<< "Q" | \
      # openssl x509 -inform pem -noout -text | \
      # awk 'BEGIN {FS=": "} /Not After/{print $2 }'

# get tls certificate
# echo "Q" used to stop  and skip connection
cert=$(echo 'Q' | openssl s_client -showcerts -connect "$domain":443 2>/dev/null )

if [ -n "$cert" ]; then
    # expirency date of a certificate for a given server
    exp_date=$(echo "$cert" | openssl x509 -noout -enddate 2>/dev/null | cut -d "=" -f 2)
    exp_date_timestamp=$(date -d "$exp_date" '+%s')
    # issuer of the certificate
    issuer=$(echo "$cert" | openssl x509 -noout -issuer 2>/dev/null )
    # remove quotes from $issuer
    issuer=$(rm_quotes "$issuer")
else
    issuer=''
fi


# calculate the number of days left to expirency
if [ -n "$exp_date" ]; then
    diff_days=$((($(date -d "$exp_date" '+%s') - $(date  '+%s'))/86400))
    has_cert=true
else
    exp_date_timestamp=''
    diff_days=''
fi
# echo a JSON string
echo "{\"domain\":\"$domain\",\"issuer\":\"$issuer\",\"cert\":\"$has_cert\",\"exp\":\"$exp_date_timestamp\",\"days_left\":\"$diff_days\"}"
