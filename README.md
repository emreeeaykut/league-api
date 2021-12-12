# league-api

> A Laravel RESTfull API project

## Build Setup

```bash
# Clone the repository
git clone https://github.com/emreeeaykut/league-api.git

# Switch to the repo folder
cd league-api

# Install all the dependencies using composer
composer install

# Copy the example env file and make the required configuration changes in the .env file
cp .env.example .env

# Run the database migrations (Set the database connection in .env before migrating)
php artisan migrate

# Run team seeder
php artisan db:seed --class="TeamSeeder"

# Run matche seeder
php artisan db:seed --class="MatcheSeeder"

# Run point seeder
php artisan db:seed --class="PointSeeder"

# Start the local development server
php artisan serve
```
