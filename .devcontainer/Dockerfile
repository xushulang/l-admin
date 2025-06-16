FROM php:8.4-fpm

RUN apt-get update && apt-get install -y git sudo chromium fonts-noto-cjk fonts-noto-color-emoji libnss3 libxss1 libasound2 libxtst6 && rm -rf /var/lib/apt/lists/*

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN install-php-extensions @composer gd opcache redis bz2 igbinary sockets pcntl zip bcmath pdo_mysql intl gmp ftp

RUN useradd -m -s /bin/bash vscode && echo "vscode ALL=(ALL) NOPASSWD:ALL" >> /etc/sudoers

USER vscode

RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.40.2/install.sh | bash && \
    export NVM_DIR="$HOME/.nvm" && \
    [ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh" && \
    [ -s "$NVM_DIR/bash_completion" ] && \. "$NVM_DIR/bash_completion" && \
    nvm install 22.14.0 && nvm use 22.14.0

WORKDIR /workspaces/l-admin
