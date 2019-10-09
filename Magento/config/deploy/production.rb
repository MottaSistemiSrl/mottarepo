# server-based syntax
# ======================
# Defines a single server with a list of roles and multiple properties.
# You can define all roles on a single server, or split them:

# server "example.com", user: "deploy", roles: %w{app db web}, my_property: :my_value
server "94.130.3.195", user: "adminburda", roles: %w{app web}, port: 5566, password: "Chq78n3_"
# set :pty, false
# set :log_level, :info
set :deploy_to, "/var/www/vhosts/burdastyle.it/deployment"
set :current_path, "/var/www/vhosts/burdastyle.it/httpdocs"

set :keep_releases, 2


namespace :deploy do

  desc "Create symlink to configured current path"
  task :create_symlink do
    on roles(:all) do

      # 1. - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
      execute "rm -f #{fetch(:current_path)} && ln -s #{release_path} #{fetch(:current_path)}"
      execute "chmod 755 #{fetch(:current_path)}" #correggo i permessi per esecuzione applicazione

      # 2. - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
      # # attivo modalità manutenzione
      #  execute "touch #{fetch(:current_path)}/maintenance.flag"
      #
      # # 3. - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
      # Importazione DB
      execute "zcat #{fetch(:current_path)}/db_burda.sql.gz | mysql -uburdastyle_prod -pYzc0*x00123kjsdf_0skd RaffiBurdaStyle_prd" #importo db
      execute "rm -r #{fetch(:current_path)}/db_burda.sql.gz" #cancello db dopo import

      # # 4. - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
      # # riattivo sito, rimuovo stato manutenzione negozio
      # execute "rm -r #{fetch(:current_path)}/maintenance.flag"

    end
  end
end

after :deploy, "deploy:create_symlink"

# ln -s /var/www/vhosts/burdastyle.it/deployment/shared/media /var/www/vhosts/burdastyle.it/deployment/releases/20170608075334/media

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


# zcat qLV0Jz9wQJburdastyle.sql.gz | mysql -uburdastyle_stage -pYzc0*x00123kjsdf_0skd RaffiBurdaStyle_stg

# zcat qLV0Jz9wQJburdastyle.sql.gz | mysql -uburdastyle_prod -pYzc0*x00123kjsdf_0skd RaffiBurdaStyle_prd
