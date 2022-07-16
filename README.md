# SMTP Server

SMTP Server based on ReactPHP.

Since those repos seem to be abandonned, I pushed a new version freely adapted to use PHP 8.1
new features, and to be more compliant with PSR interfaces (PSR-3 for logging, PSR-14 for events).
* Widely inspired from [smalot/smtp-server](https://github.com/smalot/smtp-server).
* Wich was widely inspired from [SAM-IT/react-smtp](https://github.com/SAM-IT/react-smtp).


Features:
* supports many concurrent SMTP connections
* supports anonymous connections
* supports PLAIN, LOGIN and CRAM-MD5 authentication methods
* use PSR-14 events dispatcher

## Limitations 

The objective of this library is not to build a full featured SMTP Server, but instead to have a simple 
way to catch mails sent to it and process them as wanted.
So this server does not actually sends the mails !

## Security

By default, `username` and `password` are not checked. However, you can override the `Server` class to implement your own logic.

````php
class MyServer extends \Lpotherat\Smtp\Server\Server
{
    /**
     * @param Connection $connection
     * @param MethodInterface $method
     * @return bool
     */
    public function checkAuth(Connection $connection, MethodInterface $method)
    {
        $username = $method->getUsername();
        $password = $this->getPasswordForUsername();
    
        return $method->validateIdentity($password);
    }
    
    /**
     * @param string $username
     * @return string
     */
    protected function getPasswordForUsername($username)
    {
        // @Todo: Load password from Database or somewhere else.
        $password = '';
    
        return $password;
    }
}
````

## Sample code

### Server side - launcher

````php
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
use React\Socket\SocketServer;
use Lpotherat\Smtp\Server\Connection;
use Lpotherat\Smtp\Server\Event\LogSubscriber;
use Lpotherat\Smtp\Server\Server;

include '../vendor/autoload.php';

//prevent web execution
if (php_sapi_name() !== 'cli'){
    exit();
}

try {
    // Simple logger to echo all logs in console
    $logger = new class implements LoggerInterface {
        use LoggerTrait;
        public function log($level, string|\Stringable $message, array $context = []): void
        {
            echo "[$level] $message\n";
        }
    };

    //Simple dispatcher to listen to all events of the mail server for logging
    $dispatcher = new class(new LogSubscriber($logger)) implements EventDispatcherInterface{
        public function __construct(
            private ?ListenerProviderInterface $listenerProvider = null){}

        public function dispatch(object $event):void
        {
            $listeners = $this->listenerProvider->getListenersForEvent($event);
            /** @var callable $listener */
            foreach ($listeners as $listener) {
                $listener($event);
                if ($event instanceof StoppableEventInterface
                    && $event->isPropagationStopped()) {
                    break;
                }
            }
        }
    };

    //Starts the mail server
    $server = new Server(
        server: new SocketServer(uri: '127.0.0.1:1025'),
        authMethods: [
            Connection::AUTH_METHOD_CRAM_MD5,
            Connection::AUTH_METHOD_PLAIN,
            Connection::AUTH_METHOD_LOGIN,
        ],
        dispatcher: $dispatcher);
}
catch(\Exception $e) {
    var_dump($e);
}
````

### Client side

````php
include 'vendor/autoload.php';

try {
    $mail = new PHPMailer();

    $mail->isSMTP();
    $mail->Host = 'localhost';
    $mail->Port = 1025;
    $mail->SMTPDebug = true;

    $mail->SMTPAuth = true;
    $mail->Username = "foo@gmail.com";
    $mail->Password = "foo@gmail.com";

    $mail->setFrom('from@example.org', 'Mailer');
    $mail->addAddress('joe@example.org', 'Joe User');     // Add a recipient
    $mail->addAddress('ellen@example.org');               // Name is optional
    $mail->addReplyTo('info@example.org', 'Information');
    $mail->addCC('cc@example.org');
    $mail->addBCC('bcc@example.org');

    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    if(!$mail->send()) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        echo 'Message has been sent';
    }
}
catch(\Exception $e) {
    var_dump($e);
}
````

### Composer

````json
{
    "require": {
        "lpotherat/reactphp-smtp-server": "dev-master"
    },
    "repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:lpotherat/reactphp-smtp-server.git"
        }
    ]
}
````
