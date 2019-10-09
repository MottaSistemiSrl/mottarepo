# config valid only for current version of Capistrano
lock "3.8.1"

set :application, "burdastyle"
set :repo_url, "http://adminstageburda:P12345stefano@stage.burdastyle.it/plesk-git/repburda"

#
# Da attivare per magento
set :linked_dirs,  [ "var", "sitemaps" ]
set :linked_files, [ "app/etc/local.xml" ]
