FROM phppm/nginx:latest

COPY ./wbs /var/www
#CMD  [" --debug=1","--app-env=dev","--bootstrap=laravel","--static-directory=public"]
CMD  ["--bootstrap=laravel","--static-directory=public/"]
