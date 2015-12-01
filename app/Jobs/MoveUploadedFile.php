<?php
/**
* MoveUploadedFile
* @package  App\Models
* @author  Gregoire Duché <greg.duche@mrkdevelopment.com>
* @copyright  M R K Development Pty Ltd.
* @license http://www.opensource.org/licenses/mit-license.html MIT License
*/
namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;


/**
 * Move the shared files to the Rackspace Cloud Files container
 */
class MoveUploadedFile extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $file;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file)
    {

        $this->file = $file;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    { 
        try {
            $file = $this->file;
            $file->status = 'PENDING';
            $file->save();

            $rackRepo = new \App\Repositories\Rackspace;
            $container = $rackRepo->getRackspaceContainer();
            
            $path = storage_path('user-data/'.$file->path);
            if ($container->uploadObject($file->path, fopen($path, 'r+'))) {
                $file->status = 'UPLOADED';
                $file->save();

                unlink($path);

                $file->status = 'PROCESSED';
                $file->save();
            }

        } catch (\Exception $e) {
            echo 'Cannot send the file';
            $file = $this->file;
            $file->status = 'FAILED';
            $file->save();
        } 
    }

   
}
