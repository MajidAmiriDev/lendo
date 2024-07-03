1.	docker-compose up -d --build
2.	docker exec laravel-app composer install
3.	docker exec laravel-app php artisan test

RESULT:


   PASS  Tests\Unit\ExampleTest

  ✓ that true is true

   PASS  Tests\Feature\ExampleTest

  ✓ the application returns a successful response                        0.10s  

   PASS  Tests\Feature\OrderTest
   
  ✓ customer not found                                                   1.20s  
  ✓ order registration success                                           0.04s  
  ✓ order registration failure                                           0.02s  

  Tests:    5 passed (14 assertions)
  Duration: 1.40s