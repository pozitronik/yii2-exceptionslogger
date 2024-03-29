<?php
declare(strict_types = 1);

namespace app\models;

use yii\base\Model;
use yii\web\IdentityInterface;

/**
 * This is the faker user model class
 *
 * @property int $id
 * @property string $username Отображаемое имя пользователя
 */
class UsersFaker extends Model implements IdentityInterface {

	public string|int $id;
	public string $username;

	/**
	 * Finds an identity by the given ID.
	 * @param string|int $id the ID to be looked for
	 * @return IdentityInterface|null the identity object that matches the given ID.
	 * Null should be returned if such an identity cannot be found
	 * or the identity is not in an active state (disabled, deleted, etc.)
	 */
	public static function findIdentity($id) {
		return new self([
			'id' => $id,
			'username' => "username-{$id}",
		]);
	}

	/**
	 * Finds an identity by the given token.
	 * @param mixed $token the token to be looked for
	 * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
	 * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
	 * @return IdentityInterface|null the identity object that matches the given token.
	 * Null should be returned if such an identity cannot be found
	 * or the identity is not in an active state (disabled, deleted, etc.)
	 */
	public static function findIdentityByAccessToken($token, $type = null):?IdentityInterface {
		return null;
	}

	/**
	 * Returns an ID that can uniquely identify a user identity.
	 * @return string|int an ID that uniquely identifies a user identity.
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Returns a key that can be used to check the validity of a given identity ID.
	 *
	 * The key should be unique for each individual user, and should be persistent
	 * so that it can be used to check the validity of the user identity.
	 *
	 * The space of such keys should be big enough to defeat potential identity attacks.
	 *
	 * The returned key is used to validate session and auto-login (if [[User::enableAutoLogin]] is enabled).
	 *
	 * Make sure to invalidate earlier issued authKeys when you implement force user logout, password change and
	 * other scenarios, that require forceful access revocation for old sessions.
	 *
	 * @return string|null a key that is used to check the validity of a given identity ID.
	 * @see validateAuthKey()
	 */
	public function getAuthKey():?string {
		return null;
	}

	/**
	 * Validates the given auth key.
	 *
	 * @param string $authKey the given auth key
	 * @return bool|null whether the given auth key is valid.
	 * @see getAuthKey()
	 */
	public function validateAuthKey($authKey):?bool {
		return true;
	}
}
