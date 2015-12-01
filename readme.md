Angular/Laravel based file sharing tool
Share your large files to anyone.

1) set un the .env with required parameters
2) run php artisan key:generate
3) run the migration
4) connect using the account:
'email' => 'admin@filesharing.com.au',
'password' => '077c8NG5wi',
5) Make sure the user-data folders in both storage and public folders are writable
6) to be able to send a request, you will need a Mailgun API key to be defined in the .env file
To fully work, you also need to set up Rackspace FIles with an appropriate container and fill this information in the .env