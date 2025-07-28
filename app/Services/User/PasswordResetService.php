<?php

namespace App\Services\User;

use App\DTOs\PasswordDto;
use App\DTOs\PasswordEmailDTO;
use App\Events\PasswordEvent;
use App\Exceptions\AuthException;
use App\Models\User;
use App\Notifications\PasswordResetNotification;
use App\Repositories\interfaces\User\PasswordResetRepositoryInterface;
use App\Repositories\interfaces\UserRepositoryInterface;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class PasswordResetService
{
  public function __construct(protected PasswordResetRepositoryInterface $passwordrepository,
  private  UserRepositoryInterface $repository){
  }

  public function sendLink(PasswordEmailDTO $dto):void
  {
    $user = $this->passwordrepository->findEmail($dto->email);
    if(! $user){
        throw AuthException::usernotExists();
    }
    $token=$this->generateResetToken($user);
    $user->notify(new PasswordResetNotification($token));



  }
   public function verify(string $token){
       try {
           $payload = JWTAuth::setToken($token)->getPayload();

           if ($payload->get('type') !== 'password_reset') {
               throw AuthException::InvalidResetTokenException();
           }

       } catch (JWTException $e) {
           throw  AuthException::InvalidResetTokenException();
       }
       $userId = $payload->get('sub');
       $user=User::find($userId);
       event(new PasswordEvent($token,$user,$userId));
     }
    public function resetPassword(PasswordDto $dto): void
    {
        $user=request()->user();

        $this->passwordrepository->updatePassword($user ,$dto->password);

//        try {
//            $payload = JWTAuth::setToken($dto->token)->getPayload();
//
//            if ($payload->get('type') !== 'password_reset') {
//                throw AuthException::InvalidResetTokenException();
//            }
//
//            $user = $this->passwordrepository->findEmail($payload->get('email'));
//
//            if (!$user) {
//                throw AuthException::usernotExists();
//            }

//
//        } catch (JWTException $e) {
//            throw AuthException::InvalidResetTokenException();
//        }
    }

    protected function generateResetToken(User $user): string
    {
        return JWTAuth::customClaims([
            'type' => 'password_reset',
            'email' => $user->email,
            'exp' => now()->addMinutes(60)->timestamp
        ])->fromUser($user);
    }

}
