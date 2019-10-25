@servers(['web' => ['ubuntu@15.164.226.185']])

@story('install')
    swap
    docker
    git
@endstory

@task('ls', ['on' => 'web'])
    ls -al
@endtask

@task('swap', ['on' => 'web'])
    sudo fallocate -l 2G /swapfile;
    sudo chmod 600 /swapfile;
    sudo mkswap /swapfile;
    sudo swapon /swapfile;
    sudo sh -c "echo '/swapfile swap swap default 0 0' >> /etc/fstab"
@endtask


@task('docker', ['on' => 'web'])
    sudo apt update; sudo apt install git -y;
    sudo apt update; sudo apt install apt-transport-https ca-certificates curl software-properties-common -y; curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -;
    sudo add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu bionic stable";
    sudo apt update; apt-cache policy docker-ce; sudo apt install docker-ce -y; sudo apt install docker-compose -y;
@endtask



@task('git', ['on' => 'web'])
    sudo apt update; sudo apt install git -y;
@endtask


@finished
    @slack('https://hooks.slack.com/services/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', '#dev-server-log')
@endfinished
