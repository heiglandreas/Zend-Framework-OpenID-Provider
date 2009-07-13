<?php
/* 
 * 
 * $Id$
 * 
 * Software License Agreement (BSD License)
 * 
 * Copyright (c) 2009, University of Portsmouth
 * All rights reserved.
 * 
 * Redistribution and use of this software in source and binary forms, with or without modification, are
 * permitted provided that the following conditions are met:
 * 
 *   Redistributions of source code must retain the above
 *   copyright notice, this list of conditions and the
 *   following disclaimer.
 * 
 *   Redistributions in binary form must reproduce the above
 *   copyright notice, this list of conditions and the
 *   following disclaimer in the documentation and/or other
 *   materials provided with the distribution.
 * 
 *   Neither the name of University of Portsmouth nor the names of its
 *   contributors may be used to endorse or promote products
 *   derived from this software without specific prior
 *   written permission of University of Portsmouth
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED
 * WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A
 * PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR
 * TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF
 * ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

/**
 * Data mapper for user detail table
 *
 * @author Lee Boynton
 */
class Default_Model_UserDetailMapper extends Default_Model_DataMapper
{
    /**
     * Saves a user detail
     * @param Default_Model_UserDetail $userDetail User detail to be saved
     */
    public function save(Default_Model_UserDetail $userDetail)
    {
        // check if key exists
        $select = $this->getDbTable()->select()
            ->where('user_id = ?', $userDetail->getId())
            ->where('key = ?', $userDetail->getKey());

        $row = $this->getDbTable()->fetchRow($select);

        $data = array
        (
            'key' => $userDetail->getKey(),
            'value' => $userDetail->getValue()
        );
        
        if (is_null($row))
        {
            // key doesn't exist, insert new key
            $data['user_id'] = $userDetail->getId();
            $this->getDbTable()->insert($data);
        }
        else
        {
            // key exists, update key
            $this->getDbTable()->update($data, array('user_id = ?' => $userDetail->getId()));
        }
    }

    /**
     * Finds all the user details associated with a user
     * @param int $id The user_id of the user who's details should be retrieved
     * @return Default_Model_UserDetail[] Array of user details
     */
    public function findAllById($id)
    {
        $resultSet = $this->getDbTable()->fetchAll($this->getDbTable()->select()->where('user_id = ?', $id));
        $entries   = array();
        foreach ($resultSet as $row)
        {
            $entry = new Default_Model_UserDetail();
            $entry->setId($row->user_id);
            $entry->setKey($row->key);
            $entry->setValue($row->value);
            $entry->setMapper($this);
            $entries[] = $entry;
        }
        return $entries;
    }

    /**
     * Gets the Zend_Db_Table class for the mapper
     * @return Zend_Db_Table
     */
    public function getDbTable()
    {
        if (null === $this->_dbTable)
        {
            $this->setDbTable('Default_Model_DbTable_UserDetail');
        }
        return $this->_dbTable;
    }
}
