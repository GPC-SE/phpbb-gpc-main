<?php
/**
 *
 * @package phpBB Extension - GPC Main
 * @copyright (c) 2016 Robet Heim
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */
namespace gpc\main\model;

class trick_family_provider
{

	private static $instance;

	protected $trick_families;

	private function __construct()
	{
		$this->trick_families = [];
		$this->trick_families[] = new trick_family('Around',
			'Arounds sind Tricks, bei denen sich der Stift durch einen Push im 90° Winkel zum jeweiligen Finger um diesen dreht.',
			'around', 'theme/images/tricks/around.png');
		$this->trick_families[] = new trick_family('Pass',
			'Bei (Finger-)Passes wird der Stift von einem Zwischenraum zu einem anderen gegeben, indem ein Finger den Stift runter oder hoch drückt und er dann von einem weiteren Finger angenommen wird.',
			'pass', 'theme/images/tricks/pass.png');
		$this->trick_families[] = new trick_family('Sonic',
			'In der Trick-Familie Sonic werden Stifte von einem Zwischenraum in einen Anderen gegeben - zum Beispiel durch eine Kombination aus einer Charge-Drehung und einem Pass.',
			'sonic', 'theme/images/tricks/sonic.png');
		$this->trick_families[] = new trick_family('Charge',
			'Charges sind Kreisförmige Bewegungen des Stiftes zwischen zwei Fingern, wobei der Stift den Zwischenraum nie verlässt.',
			'charge', 'theme/images/tricks/charge.png');
		$this->trick_families[] = new trick_family('Infinity',
			'Das Merkmal der Infinity-Familie ist, dass der Stift im Gegensatz zu den anderen Familien, an der Spitze (bzw. am Ende) des Stiftes angefasst wird. Dadurch wird es schwerer diese in Combos einzubauen. Es sei aber angemerkt, dass die verschiedenen Infinity-Variationen recht einfach miteinander kombinierbar sind.',
			'infinity', 'theme/images/tricks/infinity.png');
		$this->trick_families[] = new trick_family('Spins',
			'Diese Trick-Familie beinhaltet all jene Tricks, welche sich auf einem bestimmten Punkt auf der Hand oder den Fingern drehen.',
			'spins', 'theme/images/tricks/spins.png');
		$this->trick_families[] = new trick_family('Shadow',
			'Shadows sind Tricks, die mit einem Push aus einem Zwischenraum heraus starten, dann eine Drehung auf einem Punkt auf Hand oder Finger machen und danach in einem Zwischenraum wieder gefangen werden.',
			'shadow', 'theme/images/tricks/shadow.png');
		$this->trick_families[] = new trick_family('Artistic',
			'Artistic-Tricks sind meistens Aerials. Für diese ist charakteristisch, dass sie mindestens eine halbe Drehung in der Luft machen ohne das sie in Kontakt mit der Hand stehen. Eine weitere Form der Artistic-Tricks sind Tricks, die aus einer Kombination mit beiden Händen und dem Stift gewisse Formen darstellen, die einen Show-Effekt haben.',
			'artistic', 'theme/images/tricks/artistic.png');
		$this->trick_families[] = new trick_family('Sonstige',
			'Hier finden alle Tricks Platz, die in keine der anderen Trick-Familien gehören.',
			'sonstige', 'theme/images/tricks/others.png');
	}

	public static function get_all()
	{
		if (!isset(self::$instance))
		{
			self::$instance = new trick_family_provider();
		}
		return self::$instance->trick_families;
	}

}