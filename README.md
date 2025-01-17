## 開発環境設定

1. Docker Desktopをインストール
2. プロジェクトをクローン
```shell
git clone https://github.com/XxPMMPERxX/OrtCraft.git

cd OrtCraft/
```
3. composer install
```shell
docker run --rm --interactive --tty --volume $PWD:/app composer install
```
4. sailコマンドのエイリアス設定 ~/.bashrc か ~/.zshrc　に追記
```bashrc
alias sail=./vendor/bin/sail
```

5. エイリアス反映
```shell
source ~/.bashrc
# or source ~/.zshrc
```

6. .env設定
```shell
cp .env.example .env
sail up -d
sail artisan key:generate
```
7. npm install
```shell
sail npm install
sail npm run dev # viteの開発サーバー起動
```

## URL
http://localhost/

## Docs
