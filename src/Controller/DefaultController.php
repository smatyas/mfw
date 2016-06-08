<?php
/**
 * This file is part of the mfw package.
 *
 * (c) Mátyás Somfai <somfai.matyas@gmail.com>
 * Created at 2016.06.03.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Smatyas\MfwApp\Controller;

use Smatyas\Mfw\Http\Request;

class DefaultController extends BaseController
{
    public function indexAction(Request $request)
    {
        return [
            'header' => $this->getHeaderContent($request),
            'var1' => date(DATE_ISO8601),
            'var2' => rand(1, 10000),
            'controller' => __METHOD__,
            'hostname' => gethostname(),
            'session_id' => session_id(),
            'session_data' => json_encode($_SESSION),
            'footer' => $this->get('templating')->render('footer.html.tpl'),
        ];
    }
}
