ip: '<?php echo $general['address_ip']; ?>'
ram: <?php echo $general['vm_memory'] . PHP_EOL; ?>
proc: <?php echo $general['vm_processor'] . PHP_EOL; ?>
box_name: '<?php echo $general['vm_box_name']; ?>'
<?php if ($general['vm_box'] === 'vb-debian7'): ?>
box_url: 'https://dl.dropboxusercontent.com/u/10765492/debian-wheezy-64.box'
<?php elseif ($general['vm_box'] === 'vw-debian7'): ?>
box_url: 'https://dl.dropboxusercontent.com/s/tp5nupuw7dltg2u/debian-7.5.0-amd64-vmware.box'
<?php elseif ($general['vm_box'] === 'vb-centos6.4'): ?>
box_url: 'https://github.com/2creatives/vagrant-centos/releases/download/v0.1.0/centos64-x86_64-20131030.box'
<?php elseif ($general['vm_box'] === 'vw-centos6.4'): ?>
box_url: 'https://github.com/2creatives/vagrant-centos/releases/download/v0.1.0/centos64-x86_64-20131030.box'
<?php endif; ?>
# SHARED FOLDERS: Use the values "vb" or "vw"
vm_provider: <?php echo $general['vm_provider']. PHP_EOL; ?>
host_name: '<?php echo $general['vm_name']; ?>'
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