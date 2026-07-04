#!/bin/sh

################################################################################
#                                                                              #
#                        Generate a Self Signed SSL Cert                       #
#                                                                              #
################################################################################

TMPLOCATION="/tb_data/tmp"
KEYSTORE="/var/www/html/keystore"

echo "Creating Self Signed SSL Certificate"

mkdir -p $TMPLOCATION

#  Generate self signed SSL Certificate
openssl rand -base64 48 > $TMPLOCATION/passphrase.txt
openssl genrsa -aes128 -passout file:$TMPLOCATION/passphrase.txt -out $TMPLOCATION/server.key 2048
openssl req -new -passin file:$TMPLOCATION/passphrase.txt -key $TMPLOCATION/server.key -out $TMPLOCATION/server.csr -subj "/C=FR/O=tb/OU=Domain Control Validated/CN=$APP_URL"
cp $TMPLOCATION/server.key $TMPLOCATION/server.key.org
openssl rsa -in $TMPLOCATION/server.key.org -passin file:$TMPLOCATION/passphrase.txt -out $TMPLOCATION/server.key
openssl x509 -req -days 36500 -in $TMPLOCATION/server.csr -signkey $TMPLOCATION/server.key -out $TMPLOCATION/server.crt

#  Move the new certificate and key to the Tech Bench directory
mkdir -p $KEYSTORE/private
mv $TMPLOCATION/server.crt $KEYSTORE/server.crt
mv $TMPLOCATION/server.key $KEYSTORE/private/server.key

#  Cleanup unneeded files
rm -rf $TMPLOCATION/server.csr $TMPLOCATION/server.key.org $TMPLOCATION/passphrase.txt
