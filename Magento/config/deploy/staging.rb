# server-based syntax
# ======================
# Defines a single server with a list of roles and multiple properties.
# You can define all roles on a single server, or split them:

# server "example.com", user: "deploy", roles: %w{app db web}, my_property: :my_value
server "94.130.3.196", user: "adminstageburda", roles: %w{app web}, port: 5566, password: "ktBy7&71"

set :deploy_to, "/var/www/vhosts/burdastyle.it/deployment"

# set :scm_passphrase, "Chq78n3_"  # The deploy user's password

set :ssh_options, {
  keys: %w(/var/www/vhosts/burdastyle.it/.ssh/id_rsa),
  forward_agent: true,
  auth_methods: %w(publickey password),
  password: "$STG$.brd",
}

#
# # server "db.example.com", user: "deploy", roles: %w{db}
#
# set :deploy_to, "/var/www/vhosts/burdastyle.it/deployment"
# set :current_path, "/var/www/vhosts/burdastyle.it/httpdocs"
# set :keep_releases, 2




############################################################

namespace :deploy do

  desc "Operazioni pre deploy su produzione"
  task :run_agent do
    on roles(:all) do
      #execute 'ssh adminstageburda@94.130.3.196 -i /var/www/vhosts/burdastyle.it/.ssh/id_rsa -p 5566'

    end
  end
end

after :deploy, "deploy:run_agent"

############################################################

#
# 1. export db
# 2. git commit
# 3. cap production deploy
# 4. update db
# 5. deleate
#

#
# COMANDI
# Export da fare in sito di staging
# mysqldump -u [uname] -p[pass] db_name table1 table2 | gzip > table_backup.sql.gz
#
# Import:
# per decompressione gz e import diretto:
# zcat /path/to/file.sql.gz | mysql -u 'root' -p your_database
#

# export con esclusione tabelle
# mysqldump -u dave -ppassword -h localhost --ignore-table=my_db_name.my_table_name my_db_name
# -------
