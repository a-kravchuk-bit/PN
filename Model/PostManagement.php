<?php 
namespace PriorNotify\UpwardConnector\Model;

use Magento\Framework\App\ResourceConnection;
use PriorNotify\UpwardConnector\Api\PostManagementInterface;
class PostManagement implements PostManagementInterface {

	const PRIOR_NOTIFY_TABLE = 'prior_notify';
    const KEY_FIELD = 'key';
    const VALUE_FIELD = 'value';

	private $resourceConnection;

	/**
     * @var ResourceConnection
     */
    public function __construct(
		ResourceConnection $resourceConnection
    ) {
		$this->resourceConnection = $resourceConnection;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getPost($token) 
	{
		$connection  = $this->resourceConnection->getConnection();
		$tableName = $connection->getTableName(self::PRIOR_NOTIFY_TABLE);

		try {
			$whereConditions = [
				$connection->quoteInto('created_at > ?', strtotime('-2 weeks')),
			];
			$connection->delete(self::PRIOR_NOTIFY_TABLE, $whereConditions);
			$data = [ self::KEY_FIELD => 'token', self::VALUE_FIELD => $token ];
			$connection->insert(self::PRIOR_NOTIFY_TABLE, $data);
			return 'success ';
		} catch (Exception $error) {
			return 'error  ' . $error;
		}
	}
}