<?php
namespace Filters;

use Chinook\Http\Header;
use Chinook\Http\HttpStatus;
use Chinook\Http\Response;
use Core\Filters\ActionFilter;

class SessionFilter extends ActionFilter
{
    private $response;

    public function __construct ( Response $response )
    {
        $this->response = $response;
    }

    private function authorize ( )
    {
        $this->response->setStatusCode(HttpStatus::HTTP_UNAUTHORIZED_401);
        $this->response->headerCollection->addHeader ( new Header ( 'WWW-Authenticate', sprintf('Basic realm="%s"', 'TwitchplusApiV1') ) );
        return array ( 'status' => 'expired' );
    }

    public function beforeFilter ( $controller )
    {
        if ( !isset ( $_SESSION['appData'] ) )
        {
            return $this->authorize ( );
        }

        return null;
    }
}

?>