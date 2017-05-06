# Datasounds

[datasounds.io](datasounds.io) website

- `git clone https://github.com/thedatasounds/website.git && cd $_`
- `composer install`
- `ENV=development php -S localhost:8888`

With Docker:
- `git clone https://github.com/thedatasounds/website.git && cd $_`
- `docker build -t datasounds.io .`

For the first time:
- `docker run -i -t -p 80:80 datasounds.io /bin/bash`
- `apachectl start`
- `certbot -d datasounds.io`
- Then Control+D to detach and exit after letsencrypt cet generation

Any other time start like this:
- `docker run -p 80:80 -d datasounds.io`


### Routes

#### /blog

Shows the blog, takes the articles from `posts` folder. It uses [mattmezza/blog-manager](https://github.com/mattmezza/blog-manager) to manage the blog.

#### /blog/[i:page]

Shows the page number `:page` of the blog.

#### /feed/rss

Prints out the feed in RSS format.

#### /[:year]/[:month]/[:name]

Shows the article `:name` from the day `:day` of the month `:month`.

#### /[:page]

Shows the page `:page` from the folder `pages`. It uses [mattmezza/blog-manager](https://github.com/mattmezza/blog-manager) to handle a basic CMS using the file system as API.

#### /api/json

Prints out the latest article in JSON format.

#### /

Shows the home page.

#### /slack

Redirects to the slack channel (used internally).