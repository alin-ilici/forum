To use this project:



1. Clone the repository.



2. Run "composer update" in the folder where you have cloned the repository.



3. Configure your parameters.yml (for example, you can use, 'localhost' at 'database_host', 'forum' at 'database_name' and the rest leave untouched).



4. Run the following commands:

- php app/console doctrine:database:create

- php app/console doctrine:migrations:diff

- php app/console doctrine:migrations:migrate -n

- php app/console doctrine:fixtures:load -n



5. Enjoy it!