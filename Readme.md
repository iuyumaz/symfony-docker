# Docker kurulumu
    Docker ortamını ayağa kaldırmak için. 
    $cd ./docker
    $docker compose up -d --build

# Container a bağlanıyoruz.

    $docker exec -it case-php bash
    $php bin/console d:d:create
    $php bin/console d:s:create
    $php bin/console doctrine:fixtures:load

# Akış

# API REGISTER

    localhost/api/register endpointine ' 
    {
	"uid" : "testuid12d3812dsad38213",
	"application" : {
		"id" : 2
	},
	"language" : "en",
	"operatingSystem" : "androidOS"
    }' bodysi ile istek atarak bir client token alıyoruz.
    - Bu clientTokenı bundan sonra bu app için headerda 
    'Authorization Bearer {clientToken}' şeklinde gönderiyoruz. Burada constraintler kullanılarak
    valid olmayan değerlerin gelişi engellendi. Bunun gibi tüm bodyli işlemlerde bu metod kullanıldı.


# API PURCHASE

    localhost/api/purchase endpointine, daha önce aldığımız clientToken ile birlikte
    '{
    "receipt" :"1238971398712398713"
    }' body si ile istek atabiliriz. Burada case de istenen duruma göre tek haneli
    bir sayı olduğu için başarılı yanıt alacaksınız. çift haneli sayılar için de 
    geçersiz yanıt alacaksınız. Hata mesajı 
    '{"code":500,"errorMessage":"Undefined exception handled. Please 
    contact with administrator."}' şeklinde gelecek.
    Burada googleApiMockClient oluşturdum. Atılan isteğe göre cache süresini 
    3600 saniyede tuttum,Subscription için ise expire_at için GMT-6 tarihleri verildi. (Düzenlenebilir.)

# API CHECK SUBSCRIPTION
    localhost/api/api/subscription-check endpointine yine daha önce aldığımız 
    client token ile sorgulama yapıp, subscription durumumuzu anlık görebiliriz.

# CALLBACK
    Callback aksiyonu için, subscriptionlistener seviyesinde changeSet e göre, 
    symfony/messenger kullanılarak messageEventleri gönderildi ve bu 
    App/Message/Handler/MessageHandler seviyesinde kontrol edildi. İleride büyüme ihtimaline göre
    Subscription olarakta işlemler ayrıldı. Yine aynı şekilde mesaj gonderimleri için ayrı eventler
    oluşturuldu, fakat gönderimde bir fark olmadığı için abstract üzerinden gönderimi sağladım.


    

    
    
    

