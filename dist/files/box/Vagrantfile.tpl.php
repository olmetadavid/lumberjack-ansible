# -*- mode: ruby -*-
# vi: set ft=ruby :

require 'yaml'

# Vagrantfile API/syntax version. Don't touch unless you know what you're doing!
VAGRANTFILE_API_VERSION = "2"

def symbolize_keys(hash)
  hash.inject({}){|result, (key, value)|
    new_key = case key
              when String then key.to_sym
              else key
              end
    new_value = case value
                when Hash then symbolize_keys(value)
                else value
                end
    result[new_key] = new_value
    result
  }
end

settings = YAML::load_file "settings.yml"
settings = symbolize_keys(settings)

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

  config.vm.box = settings[:box_name]
  config.vm.box_url = settings[:box_url]

  # Create a forwarded port mapping which allows access to a specific port
  # within the machine from a port on the host machine. In the example below,
  # accessing "localhost:8080" will access port 80 on the guest machine.
  # config.vm.network "forwarded_port", guest: 80, host: 8080

  # Create a private network, which allows host-only access to the machine
  # using a specific IP.

  # IP Address of the VM
  config.vm.network :private_network, ip: settings[:ip]

  # Hostname of the VM (use in ansible inventory : ansible/vagrant_hosts)
  config.vm.hostname = settings[:host_name]

  # Deactivation of the default share
  config.vm.synced_folder ".", "/vagrant", id: "vagrant-root", disabled: true

  # Share of the current folder
  if settings[:synced_folders]
    settings[:synced_folders].each do |sf_name, sf|

      if settings[:vm_provider] == 'vw'
        config.vm.synced_folder sf[:host_path], sf[:guest_path_nfs], :nfs => true, :mount_options => ['nolock,vers=3,udp']
        config.bindfs.bind_folder sf[:guest_path_nfs], sf[:guest_path]
      else
        config.vm.synced_folder sf[:host_path], sf[:guest_path], :nfs => true
      end

    end
  end

  # Provider-specific configuration so you can fine-tune various
  # backing providers for Vagrant. These expose provider-specific options.
  config.vm.provider :vmware_fusion do |v|
    v.vmx["memsize"] = settings[:ram]
    v.vmx["numvcpus"] = settings[:proc]
  end

  config.vm.provider :virtualbox do |v|
    # Don't boot with headless mode
    v.gui = false

    v.customize [ "modifyvm", :id, "--cpus", settings[:proc] ]
    v.customize [ "modifyvm", :id, "--memory", settings[:ram] ]
  end


  # Provisioning Ansible
  #  * http://docs.vagrantup.com/v2/provisioning/ansible.html
  #  * http://docs.ansible.com/guide_vagrant.html
  config.vm.provision "ansible" do |ansible|
    ansible.inventory_path = "ansible/inventories/vagrant"
    ansible.playbook = "ansible/app.yml"
    ansible.extra_vars = {
      uservar: "vagrant"
    }
    ansible.limit= "all"
    ansible.verbose = "vvvv"
  end


  # PLUGINS
  config.vm.provider :virtualbox do |v|
    config.vbguest.auto_update = true
    config.vbguest.no_remote = false
  end

end
