<?php
if ( !defined( 'MEDIAWIKI' ) ) die();
/**
 * Statistic output classes.
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
 * @ingroup MaintenanceLanguage
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @author Antoine Musso <hashar at free dot fr>
 */

/** A general output object. Need to be overriden */
class statsOutput {
	function formatPercent( $subset, $total, $revert = false, $accuracy = 2 ) {
		return @sprintf( '%.' . $accuracy . 'f%%', 100 * $subset / $total );
	}

	# Override the following methods
	function heading() {
	}
	function footer() {
	}
	function blockstart() {
	}
	function blockend() {
	}
	function element( $in, $heading = false ) {
	}
}

/** Outputs WikiText */
class wikiStatsOutput extends statsOutput {
	function heading() {
		global $wgDummyLanguageCodes;
		$version = SpecialVersion::getVersion( 'nodb' );
		echo "'''Statistics are based on:''' <code>" . $version . "</code>\n\n";
		echo "'''Note:''' These statistics can be generated by running <code>php maintenance/language/transstat.php</code>.\n\n";
		echo "For additional information on specific languages (the message names, the actual problems, etc.), run <code>php maintenance/language/checkLanguage.php --lang=foo</code>.\n\n";
		echo 'English (en) is excluded because it is the default localization';
		if( is_array( $wgDummyLanguageCodes ) ) {
			$dummyCodes = array();
			foreach( $wgDummyLanguageCodes as $dummyCode => $correctCode ) {
				$dummyCodes[] = Language::fetchLanguageName( $dummyCode ) . ' (' . $dummyCode . ')';
			}
			echo ', as well as the following languages that are not intended for system message translations, usually because they redirect to other language codes: ' . implode( ', ', $dummyCodes );
		}
		echo ".\n\n"; # dot to end sentence
		echo '{| class="sortable wikitable" border="2" style="background-color: #F9F9F9; border: 1px #AAAAAA solid; border-collapse: collapse; clear:both; width:100%;"' . "\n";
	}
	function footer() {
		echo "|}\n";
	}
	function blockstart() {
		echo "|-\n";
	}
	function blockend() {
		echo '';
	}
	function element( $in, $heading = false ) {
		echo ( $heading ? '!' : '|' ) . "$in\n";
	}
	function formatPercent( $subset, $total, $revert = false, $accuracy = 2 ) {
		$v = @round( 255 * $subset / $total );
		if ( $revert ) {
			# Weigh reverse with factor 20 so coloring takes effect more quickly as
			# this option is used solely for reporting 'bad' percentages.
			$v = $v * 20;
			if ( $v > 255 ) $v = 255;
			$v = 255 - $v;
		}
		if ( $v < 128 ) {
			# Red to Yellow
			$red = 'FF';
			$green = sprintf( '%02X', 2 * $v );
		} else {
			# Yellow to Green
			$red = sprintf( '%02X', 2 * ( 255 - $v ) );
			$green = 'FF';
		}
		$blue = '00';
		$color = $red . $green . $blue;

		$percent = parent::formatPercent( $subset, $total, $revert, $accuracy );
		return 'style="background-color:#' . $color . ';"|' . $percent;
	}
}

/** Output text. To be used on a terminal for example. */
class textStatsOutput extends statsOutput {
	function element( $in, $heading = false ) {
		echo $in . "\t";
	}
	function blockend() {
		echo "\n";
	}
}

/** csv output. Some people love excel */
class csvStatsOutput extends statsOutput {
	function element( $in, $heading = false ) {
		echo $in . ";";
	}
	function blockend() {
		echo "\n";
	}
}
