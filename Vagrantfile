# Fix permissions after you run commands on both hosts and guest machine
if !Vagrant::Util::Platform.windows?
  system("
      if [ #{ARGV[0]} = 'up' ]; then
          echo 'Setting group write permissions for ./logs/*'
          chmod 775 ./logs
          chmod 664 ./logs/*
      fi
  ")
end

VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.box = "puppetlabs/centos-6.6-64-nocm"
  config.vm.network "private_network", ip: "192.168.51.60"

  # Configure cached packages to be shared between instances of the same base box.
  # More info on http://fgrehm.viewdocs.io/vagrant-cachier/usage
  if Vagrant.has_plugin?("vagrant-cachier")
      config.cache.scope = :box
  end

  # Make sure logs folder is owned by apache with group vagrant
  config.vm.synced_folder "logs", "/vagrant/logs", owner: 48, group: 500

  # Install all needed packages
  config.vm.provision "shell", name: "rpm", inline: <<-SHELL
    yum -y update ca-certificates
    yum -y update nss
    rpm -Uvh https://mirror.webtatic.com/yum/el6/latest.rpm
    yum install -y epel-release
  SHELL

  # PHP and modules
  config.vm.provision "shell", name: "php", inline: <<-SHELL
    yum -y install mod_php71w php71w-opcache php71w-cli
    yum -y install mod_ssl
    yum -y install php71w-xml
  SHELL

  # # Use the provided example environment
  # config.vm.provision "shell", name: "environment", inline: <<-SHELL
  #   cd /vagrant && cp .env.example .env
  # SHELL

  # Update Apache config and restart
  config.vm.provision "shell", name: "apache", inline: <<-'SHELL'
    #sed -i -e "s/DocumentRoot \"\/var\/www\/html\"/DocumentRoot \/vagrant\/public/" /etc/httpd/conf/httpd.conf
    # If you prefer Slim app to be in subfolder comment above and uncomment below
    echo "Alias /test/ /vagrant/public/" >> /etc/httpd/conf/httpd.conf
    echo "EnableSendfile off" >> /etc/httpd/conf/httpd.conf
    sed -i -e "s/ErrorLog logs\/error_log/ErrorLog \/vagrant\/logs\/error_log/" /etc/httpd/conf/httpd.conf
    sed -i -e "s/CustomLog logs\/access_log/CustomLog \/vagrant\/logs\/access_log/" /etc/httpd/conf/httpd.conf
    sed -i -e "s/AllowOverride None/AllowOverride All/" /etc/httpd/conf/httpd.conf

    sed -i -e "s/ErrorLog logs\/ssl_error_log/ErrorLog \/vagrant\/logs\/error_log/" /etc/httpd/conf.d/ssl.conf
    sed -i -e "s/TransferLog logs\/ssl_access_log/TransferLog \/vagrant\/logs\/access_log/" /etc/httpd/conf.d/ssl.conf

    /etc/init.d/httpd restart
    /sbin/chkconfig --levels 235 httpd on

    # Make sure Apache also runs after vagrant reload
    echo "# Start Apache after /vagrant is mounted" > /etc/init/httpd.conf
    echo "start on vagrant-mounted" >> /etc/init/httpd.conf
    echo "exec /etc/init.d/httpd restart" >> /etc/init/httpd.conf
  SHELL

  # Stop iptable because it will cause too much confusion
  config.vm.provision "shell", name: "iptables", inline: <<-SHELL
    /etc/init.d/iptables stop
    /sbin/chkconfig iptables off
  SHELL

  config.vm.post_up_message = <<MESSAGE

  ███████╗██╗     ██╗███╗   ███╗
  ██╔════╝██║     ██║████╗ ████║
  ███████╗██║     ██║██╔████╔██║
  ╚════██║██║     ██║██║╚██╔╝██║
  ███████║███████╗██║██║ ╚═╝ ██║
  ╚══════╝╚══════╝╚═╝╚═╝     ╚═╝

 You can access me at: http://192.168.51.60/test/

MESSAGE

end