<?php 
namespace PriorNotify\UpwardConnector\Api;


interface PostManagementInterface {


	/**
	 * GET for Post api
	 * @param string $token
	 * @return string
	 */
	
	public function getPost($token);
}