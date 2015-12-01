<?php
/**
* User Token
* @package  App\Services
* @author  Gregoire Duché <greg.duche@mrkdevelopment.com>
* @copyright  M R K Development Pty Ltd.
* @license http://www.opensource.org/licenses/mit-license.html MIT License
*/
namespace App\Services;

/**
 *  Deals with User Token Controllers
 */
class UserToken {


    /**
       * Checks the token and authenticates the users if present.
       *
       * @param [type] $token [description]
       *
       * @return [type] [description]
       */
      public static function checkAndAuthToken($request) {

        $authenticated = false;
        $authInfo = [
          'token' => $request->header('X-Auth-Token'),
          'app_id' => $request->header('X-App-ID'),
        ];
        


        if ($authInfo['token']) {
            if ($authInfo['app_id']) {
              $userToken = \App\Models\UserToken::where('token', '=', $authInfo['token'])
                                                ->where('application_id', '=', $authInfo['app_id'])
                                                ->first();  

            } else {
              $userToken = \App\Models\UserToken::where('token', '=', $authInfo['token'])
                                                ->first();
            }

            if (!$userToken) {
                return false;
            }

            $date = new \DateTime($userToken->updated_at);
            $timestamp = $date->format('U');
            $delay = time() - $timestamp;
            $timeInMinutes = $delay/60;

            if ($authInfo['app_id']) {
              $expires = env($authInfo['app_id'].'.expires', 0);
            } else {
              $expires = \Config::get('FS.expires');

            }

            if ($expires && $timeInMinutes < $expires) {
              $user = \App\Models\User::find($userToken->user_id);

              if ($user) {
                  if (\Auth::loginUsingId($user->id)) {
                    self::refreshToken($user);
                    $authenticated = true;
                  } 
              }
            }
          }
        return $authenticated;
      }


    /**
     * getToken
     *
     * Will create a new token or refresh the existing one on a successfull
     * authentication
     * @return String the generated token
     */
    public static function getToken()
    {
        $inputToken = \Request::header('X-Auth-Token');
        if ($inputToken == '') {
          $inputToken = \Session::get('token_input');
        }

        $applicationId = \Request::header('X-App-ID');
        if ($applicationId == '') {
          $applicationId = \Session::get('token_app_id');
        }


        $token = '';
        
        if (\Auth::check()) {
            $user = \Auth::user();
            $userToken = \App\Models\UserToken::where('user_id', $user->id)
                            ->where('application_id', $applicationId)
                            ->first();

            if (!$userToken) {
                $userToken = new \App\Models\UserToken;
                $userToken->user_id = $user->id;
                $userToken->application_id = $applicationId;
                $userToken->token = hash('sha256', str_random(10), false);
            }

            $token = $userToken->token;

            $userToken->updated_at = time();
            $userToken->save();
        } else {

            if ($applicationId) {
              $userToken = \App\Models\UserToken::where('token', $inputToken)
                ->where('application_id', $applicationId)
                ->first();
            } else {
              $userToken = \App\Models\UserToken::where('token', $inputToken)
                ->whereNull('application_id')
                ->first();
            }

            if ($userToken) {
                $user_id   = $userToken->user_id;
                \Auth::loginUsingId($user_id);
                $user = \Auth::user();
                $userToken = self::refreshToken($user);
                $token = $userToken->token;
            }
        }
        // Session::put('token', $token);
        return $token;
    }


    /**
     * Refreshing
     * @return [type]
     */
    public static function refreshToken($user = null, $applicationId = null) {

      if ($user == null) { 
        self::generateNewToken($user);
      }

      $userToken = \App\Models\UserToken::where('user_id', '=', $user->id)->where('application_id', '=', $applicationId)->first();
      if ($userToken) {
        $userToken->updated_at = \Carbon\Carbon::now();
        $userToken->save();
      } else {
        $userToken = self::generateNewToken($user, $applicationId);
      }
      return $userToken;
    }

    public static function generateNewTokenObject(\App\Models\User $user, $applicationId = null) {
        
        $token = hash('sha256', str_random(10), false);

        $userToken = \App\Models\UserToken::firstOrNew(['user_id' => $user->id, 'application_id' => $applicationId]);

        $userToken->user_id = $user->id;
        $userToken->application_id = $applicationId;
        $userToken->token = $token;
        $userToken->save();

        return $userToken;
    } 

    public static function generateNewToken(\App\Models\User $user, $applicationId = null)
    {
        $userToken = self::generateNewTokenObject($user, $applicationId);
        return $userToken->token;
    }

    
    /**
     * Checks the token and authenticates the users if present.
     *
     * @param [type] $token [description]
     *
     * @return [type] [description]
     */
    public function check($token)
    {
        $userToken = UserToken::where('token', '=', $token)->first();

        if (!$userToken) {
            return false;
        }

        $date = new DateTime($userToken->updated_at);
        $timestamp = $date->format('U');
        $delay = time() - $timestamp;
        $timeInMinutes = $delay/60;

        $expires = Session::get('token_expires');
        if ($expires && $timeInMinutes < $expires) {
          $user = Sentry::findUserById($userToken->user_id);

          if ($user) {
              return true;
          }
        }

        return false;
    }
}
