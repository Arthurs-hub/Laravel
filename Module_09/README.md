# Практическая работа

## Цели практической работы

Научиться:

-   создавать события и вызывать их;
    
-   создавать слушатели и привязывать их к «событию» вложенные шаблоны на практике;
    
-   применять наблюдатели моделей;
    

## Что нужно сделать

1.  Создайте новый проект Laravel или откройте уже существующий
    
2.  Создайте новую ветку вашего репозитория от корневой (main или master).
    
3.  Создайте миграцию командой php artisan make:migration CreateNewsTable со следующими полями:  
    ![](https://lh5.googleusercontent.com/ZaQwHxuA9qQJSoTlYCHmfUyCa7JkVBuYvWDtD_dXt33OFqMDJx5GB6CgEqGz96YD_rfG0mWM62vcJj3NKspndVlGVVN4DBNXM_BqZJFtnm4pY27Uey8lgatMzyzg-6vxRANy3tvkcRTNCYV-ReQxEH8)
    
4.  Создайте модель News
    
5.  Создайте событие NewsHidden и присвойте полю класса $news параметр $news в конструкторе класса.  
    ![](https://lh3.googleusercontent.com/Prl-OIY9ENXQRGTVmIpm8UTPxYPy5ShafcaWGdMdLX0NAoJSCank5vbnlbkgA6ojltWyy1OGoTWVauTLJUr6389ZnUvnchHuVVA7ah49x0mpiuUkyZAY6YkD__cXl2lPMaeCmlXlYh6ZdFO5TX_n_9c)
    
6.  Создайте слушатель NewsHiddenListener, в котором опишите логику слушателя используя функцию:  
    Log::info(‘News ’ . $event->news->id . ‘ hidden’);
    
7.  Зарегистрируйте событие и слушатель в классе EventServiceProvider.
    
8.  В файле routes/web.php создайте необходимый маршрут ‘/news/create-test’ использующий метод get для создания тестовой новости и пропишите логику создания тестовой новости.  
    ![](https://lh3.googleusercontent.com/6rVGZfbqTgKTP3DuhA29YKbCfpsXRrdLYhU5BuWlnDui-wtQvT-i4ths9LUq2K-1ioCNQWcozt885ftWSWoS067eVevAJ70xbMZvnm8jpX49EluHMSp6oLfIiwS13rWJ9LFfxFM5i8afwwTWxPJkfpI)
    
9.  В файле routes/web.php создайте необходимый маршрут использующий метод get  ‘/news/{id}/hide’  для скрытия новости. Измените атрибут is_hidden на значение true. После этой операции вызовите событие NewsHidden с помощью инструкции NewsHidden::dispatch($news);  
    ![](https://lh3.googleusercontent.com/PWH_7BV2ON_3YNiYrX44z00xonG8WlCfCzIZLbY2lD90pOw7Jk1A72_b9H_APm1ybozrOnInEX0d48dOHc2pt6reQA-iUeIYx9IfgZie7y0L4eNwt_pCF9uOX7e0psoh6l89hyeTqqe-PYXOyslih7M)
    
10.  В файле storage/logs/laravel.log проверьте сработал ли слушатель, в нем должна появится строка ‘News hidden 1’, где 1 - это id скрытой новости (может отличаться)
    
11.  Создайте класс наблюдатель NewsObserver
    
12.  Зарегистрируйте его в файле App\Providers\EventServiceProvider в функции boot
    
13.  Опишите логику изменения поля slug новости при вызове события saving в наблюдателе NewsObserver с помощью инструкции.  
    ![](https://lh6.googleusercontent.com/45O68C7-45SYVTUbjkLXnt2NUoYaV24H8P55Hjrx2lPs8ZnC5kS1n9rQy80O5uDCh--mGreNca4xTx6NPRaGrr6OVaK5ZaNu4nFAPm4VpRV4XJ6UAg88dIhXIo071jgUbdjwoWxKMO7-Y1nKRq68dC4)  
    Данная инструкция использует класс Str, который можно подключить с помощью инструкции в начале файла  
    ![](https://lh5.googleusercontent.com/Bjwi48cVxEpdDvOggEkVH3AmIAKAb98FIKSByFb_GoByAkQ1SWIxtN3O4YX_FlU7VgibPw87qT1DwVX6WJEkNPBWUQzRYFio2pSGVhrzlMDqXK_FqVQcwV3fzSMGzVcSM3Y7OL7tdvW6Pvxul_hM440)
    
14.  Создайте еще одну новость с помощью маршрута ‘/news/create-test’.
    
15.  Проверьте заполнение поля slug через базу данных. Оно должно выглядеть следующим образом - «test-news-title» (в случае если вы оставили название как в примере).
    
16.  Сделайте коммит изменений с помощью Git и отправьте push в репозиторий.
    

## Критерии оценки

Принято:

-   выполнены все пункты задания;
    
-   в работе используются указанные инструменты и соблюдены условия;
    
-   код корректно отформатирован по стандартам программирования на PHP;
    
-   код запускается, выводит различные данные на экран, не вызывает ошибок.
    

  

На доработку:

-   выполнены не все обязательные пункты задания;
    
-   задание выполнено с ошибками.
    

## Как отправить работу на проверку

-   Отправьте коммит с кодом задания в ветку master в вашем репозитории.
    
-   Отправьте URL вашего репозитория через форму отправки на платформе Skillbox.
