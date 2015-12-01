<?php
/**
* Files repository
* @package  App\Repositories
* @author  Gregoire Duché <greg.duche@mrkdevelopment.com>
* @copyright  M R K Development Pty Ltd.
* @license http://www.opensource.org/licenses/mit-license.html MIT License
*/
namespace App\Repositories;

use Event;
use Exception;
use Flow\Config as FlowConfig;
use Flow\File as FlowFile;
use Validator;
use App\Models\File as Model;
use App\Models\User;

/**
 * Files related actions and methods
 */
class Files
{

    /**
     * Handles the file upload
     *
     * @param array $input [description]
     *
     * @return array Variable response - either pending or file uploaded when all chunks
     *               are received.
     */
    public function handle($input = array())
    {
        $validator = Validator::make(
            $input,
            Model::$rules
        );

        $user = User::find($input['user_id']);

        if ($validator->fails()) {
            throw new Exception("Validation Issues", 500);
        }

        $filename                   = time() . $input['flowFilename'];
        $input['original_filename'] = $input['flowFilename'];
        $extension                  = pathinfo($filename, PATHINFO_EXTENSION);
        $filename                   = pathinfo($filename, PATHINFO_FILENAME);

        if (!isset($input['path'])) {
            $input['path'] = $user->id . '/';
        }

        $storageLocation = storage_path('user-data/' . $input['path']);

        if (!is_dir($storageLocation)) {
            mkdir($storageLocation, 0755, true);
        }

        $config = new FlowConfig();

        if (!is_dir($storageLocation . 'chunks')) {
            mkdir($storageLocation . 'chunks', 0755, true);
        }

        $config->setTempDir($storageLocation . 'chunks');
        $file = new FlowFile($config);

        if (isset($_POST['ie-app'])) {
            $file->saveChunk();
        } else {
            if ($file->validateChunk()) {
                $file->saveChunk();
            } else {
                // error, invalid chunk upload request, retry
                throw new Exception('Bad request', 400);
            }
        }

        $filename  = $this->sanitizeString($filename) . '.' . $extension;

        $localPath = $storageLocation . $filename;

        if ($file->validateFile() && $file->save($localPath)) {
            $input['status'] = 'saved';
            $input['size']   = $input['flowTotalSize'];

            if (isset($_POST['ie-app'])) {
                $input['size'] = filesize($localPath);
            } else {
                $input['size'] = $input['flowTotalSize'];
            }
            $input['path']     = $input['path'] . $filename;
            $input['filename']     = $filename;
            $input['type']     = mime_content_type($localPath);
            $file              = Model::create($input);
            

            //FIXME should use the transformer
            return ['id' => $file->id, 'path' => $file->path, 'links' => $file->links, 'original_filename' => $file->original_filename];
        } else {
            // This is not a final chunk, continue to upload
            return array('pending' => true);
        }
    }

    /**
     * Santizise a given string
     *
     * @param string $string Input string
     *
     * @return string Sanitised string
     */
    private function sanitizeString($string = null)
    {
        if (empty($string)) {
            throw new \InvalidArgumentException('No input string is given');
        }
        $string = strip_tags($string);
        // Preserve escaped octets.
        $string = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $string);
        // Remove percent signs that are not part of an octet.
        $string = str_replace('%20', '', $string);
        $string = str_replace('%', '', $string);

        if (function_exists('mb_strtolower')) {
            $string = mb_strtolower($string, 'UTF-8');
        } else {
            $string = strtolower($string);
        }
        //FIXME:: $string = preg_replace('/\p{Mn}/u', '', \Normalizer::normalize($string, \Normalizer::FORM_KD));
        $string = preg_replace('/[^%a-z0-9 _-]/', '', $string);
        $string = preg_replace('/\s+/', '-', $string);
        $string = preg_replace('|-+|', '-', $string);
        $string = trim($string, '-');

        return $string;
    }


    /**
     * Get Resource
     *
     * @param integer $id Resource ID
     *
     * @return App\Models\AppFile Resource instance
     */
    public function get($id)
    {
        $resource = Model::find($id);

        if (!$resource) {
            throw new Exception("Resource Not Found", 404);
        }

        return $resource;
    }


    /**
     * Delete a resource
     *
     * @param integer $id Resource ID
     *
     * @return boolean Confirmation of delete
     */
    public function delete($id)
    {
        $resource = $this->get($id);
        $file = '';

        $file = storage_path($this->path);
        
        if (!unlink($file)) {
            throw new Exception("Error while deleting file", 404);
        }

        return $resource->delete();
    }
}
