<?php
/**
 * Job queue aggregator code.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @author Aaron Schulz
 */

/**
 * Class to handle tracking information about all queues
 *
 * @ingroup JobQueue
 * @since 1.21
 */
abstract class JobQueueAggregator {
	/** @var JobQueueAggregator */
	protected static $instance = null;

	/**
	 * @param array $params
	 */
	protected function __construct( array $params ) {}

	/**
	 * @return JobQueueAggregator
	 */
	final public static function singleton() {
		global $wgJobQueueAggregator;

		if ( !isset( self::$instance ) ) {
			$class = $wgJobQueueAggregator['class'];
			$obj = new $class( $wgJobQueueAggregator );
			if ( !( $obj instanceof JobQueueAggregator ) ) {
				throw new MWException( "Class '$class' is not a JobQueueAggregator class." );
			}
			self::$instance = $obj;
		}

		return self::$instance;
	}

	/**
	 * Destroy the singleton instance
	 *
	 * @return void
	 */
	final public static function destroySingleton() {
		self::$instance = null;
	}

	/**
	 * Mark a queue as being empty
	 *
	 * @param string $wiki
	 * @param string $type
	 * @return bool Success
	 */
	final public function notifyQueueEmpty( $wiki, $type ) {
		wfProfileIn( __METHOD__ );
		$ok = $this->doNotifyQueueEmpty( $wiki, $type );
		wfProfileOut( __METHOD__ );
		return $ok;
	}

	/**
	 * @see JobQueueAggregator::notifyQueueEmpty()
	 */
	abstract protected function doNotifyQueueEmpty( $wiki, $type );

	/**
	 * Mark a queue as being non-empty
	 *
	 * @param string $wiki
	 * @param string $type
	 * @return bool Success
	 */
	final public function notifyQueueNonEmpty( $wiki, $type ) {
		wfProfileIn( __METHOD__ );
		$ok = $this->doNotifyQueueNonEmpty( $wiki, $type );
		wfProfileOut( __METHOD__ );
		return $ok;
	}

	/**
	 * @see JobQueueAggregator::notifyQueueNonEmpty()
	 */
	abstract protected function doNotifyQueueNonEmpty( $wiki, $type );

	/**
	 * Get the list of all of the queues with jobs
	 *
	 * @return Array (job type => (list of wiki IDs))
	 */
	final public function getAllReadyWikiQueues() {
		wfProfileIn( __METHOD__ );
		$res = $this->doGetAllReadyWikiQueues();
		wfProfileOut( __METHOD__ );
		return $res;
	}

	/**
	 * @see JobQueueAggregator::getAllReadyWikiQueues()
	 */
	abstract protected function doGetAllReadyWikiQueues();

	/**
	 * Get all databases that have a pending job.
	 * This poll all the queues and is this expensive.
	 *
	 * @return Array (job type => (list of wiki IDs))
	 */
	protected function findPendingWikiQueues() {
		global $wgLocalDatabases;

		$pendingDBs = array(); // (job type => (db list))
		foreach ( $wgLocalDatabases as $db ) {
			foreach ( JobQueueGroup::singleton( $db )->getQueuesWithJobs() as $type ) {
				$pendingDBs[$type][] = $db;
			}
		}

		return $pendingDBs;
	}
}
