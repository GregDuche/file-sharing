<?php
/**
* SharedFiles repository
* @package  App\Repositories
* @author  Gregoire Duché <greg.duche@mrkdevelopment.com>
* @copyright  M R K Development Pty Ltd.
* @license http://www.opensource.org/licenses/mit-license.html MIT License
*/
namespace App\Repositories;

use App\FileSharing\Models\FileShareDownload;
use App\Models\File as FSFile;

/**
 * Actions related to a file sharing request
 */
class SharedFiles {

	/**
	 * File sharing request.
	 * @var [type]
	 */
	protected $request;

	public function setRequest($request) {
		$this->request = $request;
	}

	 /**
     * Get Resource
     * @param integer $id Resource ID
     * @return Viz\Models\User Resource instance
     */
    public function get($id)
    {
        $resource = \App\Models\SharedFile::find($id);

        if (!$resource) {
            throw new Exception("Resource Not Found", 404);
        }

        return $resource;
    }

    /**
     * Delete a resource
     * @param integer $id Resource ID
     * @return boolean Confirmation of delete
     */
    public function delete($id)
    {
        $resource = $this->get($id);
        if ($resource->delete()) {
            return true;
        }

        return false;
    }

	/**
     * Search
     * @param array $input Input params
     * @return Illuminate\Database\Query
     */
    public function search($input)
    {
        $query = \App\Models\SharedFile::search($input);
        return $query;
    }

	/**
	 * Create a file sharing request
	 */
	public function create($input) {

		$user = \Auth::user();
		$input['user_id'] = $user->id;

		$now  = \Carbon\Carbon::now();
		$input['expires_at'] = $now->addDays($input['expires_at'])
		                           ->toDateTimeString();


		$request = \App\Models\SharedFileRequest::create($input);
		
		if ($request) {
			foreach ($input['files'] as $file) {
				$fileShare = \App\Models\SharedFile::create([
					'request_id' => $request->id,
					'file_id'    => $file['id'],
					'user_id' => $user->id,
				]);

				$file = FSFile::find($file['id']);

				$file->resolvable_type = 'FileShare';
				$file->resolvable_id   = $fileShare->id;
				$file->save();
			}

			foreach ($input['recipients'] as $recipient) {
				$d = \App\Models\SharedFileRecipient::create([
					'request_id' => $request->id,
					'email'      => $recipient,
				]);
			}

			\Event::fire(new \App\Events\FileShared($request));

		}

		return $request;
	}
}
