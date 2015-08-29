To use this project:



1. Clone the repository.



2. Change the branch to the one called 'production'.



3. Run "composer update" in the folder where you have cloned the repository (it is best to accept the default parameters that are configured when you're asked for the paramters.yml file).



4. Run the following commands:

- php app/console doctrine:database:create

- php app/console doctrine:migrations:diff

- php app/console doctrine:migrations:migrate -n

- php app/console doctrine:fixtures:load -n



Enjoy it!