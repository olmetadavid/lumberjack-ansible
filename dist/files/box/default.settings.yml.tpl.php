ip: '<?php echo $general['address_ip']; ?>'
ram: 2048
proc: 1
box_name: '<?php echo $general['vm_name']; ?>'
# VIRTUALBOX:
box_url: 'https://dl.dropboxusercontent.com/u/10765492/debian-wheezy-64.box'
# VMWARE:
#box_url: 'https://dl.dropboxusercontent.com/s/tp5nupuw7dltg2u/debian-7.5.0-amd64-vmware.box'
# SHARED FOLDERS: Use the values "vb" or "vw"
vm_provider: vb
host_name: 'carrefour-dev'
forwards:
synced_folders:
  store:
    host_path: '.'
    guest_path: '/home/vagrant/www/portal'
    # ONLY FOR VMWARE USE:
    guest_path_nfs: '/home/vagrant/www/portal-nfs'
    owner: 'vagrant'
    group: 'vagrant'
    extra: ['dmode=775', 'fmode=775']