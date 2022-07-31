# get nginx from source
FROM nginx:alpine

# install nano to read and edit something on dev
# RUN apk add nano

# install nginx extras
#RUN apt-get update && apt-get install -y nginx-extras
#RUN apk update


# run https
RUN apk add openssl
RUN openssl req -x509 -nodes -days 3650 -subj "/C=CA/ST=QC/O=MrAdib/CN=mradib.com" -addext "subjectAltName=DNS:mradib.com" -newkey rsa:2048 -keyout /etc/ssl/private/nginx-selfsigned.key -out /etc/ssl/certs/nginx-selfsigned.crt


# remove default config file @todo: check
#RUN rm /etc/nginx/nginx.conf /etc/nginx/conf.d/default.conf

# copy nginx conf folder @todo: check
# COPY docker/nginx/conf /etc/nginx

# set volumes
#VOLUME /usr/share/nginx/html
#VOLUME /etc/nginx




# old config --remove
COPY docker/nginx/default.conf /etc/nginx/conf.d
# COPY ./src/ /var/www/html