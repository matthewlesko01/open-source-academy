<?php
/**
 *
 *
 * Created on Feb 2, 2009
 *
 * Copyright © 2009 Roan Kattouw "<Firstname>.<Lastname>@gmail.com"
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
 */

/**
 * Formatter that spits out anything you like with any desired MIME type
 * @ingroup API
 */
class ApiFormatRaw extends ApiFormatBase {

	private $errorFallback;

	/**
	 * @param ApiMain $main
	 * @param ApiFormatBase $errorFallback Object to fall back on for errors
	 */
	public function __construct( ApiMain $main, ApiFormatBase $errorFallback ) {
		parent::__construct( $main, 'raw' );
		$this->errorFallback = $errorFallback;
	}

	public function getMimeType() {
		$data = $this->getResultData();

		if ( isset( $data['error'] ) ) {
			return $this->errorFallback->getMimeType();
		}

		if ( !isset( $data['mime'] ) ) {
			ApiBase::dieDebug( __METHOD__, 'No MIME type set for raw formatter' );
		}

		return $data['mime'];
	}

	public function initPrinter( $unused ) {
		$data = $this->getResultData();
		if ( isset( $data['error'] ) ) {
			$this->errorFallback->initPrinter( $unused );
		} else {
			parent::initPrinter( $unused );
		}
	}

	public function closePrinter() {
		$data = $this->getResultData();
		if ( isset( $data['error'] ) ) {
			$this->errorFallback->closePrinter();
		} else {
			parent::closePrinter();
		}
	}

	public function execute() {
		$data = $this->getResultData();
		if ( isset( $data['error'] ) ) {
			$this->errorFallback->execute();
			return;
		}

		if ( !isset( $data['text'] ) ) {
			ApiBase::dieDebug( __METHOD__, 'No text given for raw formatter' );
		}
		$this->printText( $data['text'] );
	}
}
