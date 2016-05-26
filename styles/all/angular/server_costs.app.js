var server_costs = angular.module('server_costs', [ 'angularUtils.directives.dirPagination' ])
	.config(
			function($interpolateProvider, $httpProvider) {
			$interpolateProvider.startSymbol('{[{').endSymbol('}]}');
		})
	.controller('PsCtrl', PsCtrl);

function PsCtrl($scope) {
	$scope.trinkgeldMessage = "";
	$scope.trinkgeld = 5;
	$scope.geberName = "";
	$scope.updateTrinkgeldMsg = function() {
		if ($scope.geberName == "") {
			$scope.trinkgeldMessage = "Anonymes Trinkgeld";
		} else {
			$scope.trinkgeldMessage = "Trinkgeld von " + $scope.geberName;
		}
	};
	$scope.updateTrinkgeld = function() {
		if ($scope.trinkgeld < 1) {
			$scope.trinkgeld = 1;
		}
	};
	$scope.updateTrinkgeldMsg();
	$scope.data = [
		{'jahr': 2011, monat: 1, geber: 'Robert Heim', werbung: 0.80734694, trinkgeld: 0.19204082, status: 'finanziert'},
		{'jahr': 2011, monat: 2, geber: 'Robert Heim', werbung: 0.93204082, trinkgeld: 0.04775510, status: 'finanziert'},
		{'jahr': 2011, monat: 3, geber: 'Robert Heim', werbung: 0.64836735, trinkgeld: 0.35122449, status: 'finanziert'},
		{'jahr': 2011, monat: 4, geber: 'Robert Heim', werbung: 0.71367347, trinkgeld: 0.28591837, status: 'finanziert'},
		{'jahr': 2011, monat: 5, geber: 'Robert Heim', werbung: 0.53487115, trinkgeld: 0.45557758, status: 'finanziert'},
		{'jahr': 2011, monat: 6, geber: 'Robert Heim', werbung: 0.62489796, trinkgeld: 0.36326531, status: 'finanziert'},
		{'jahr': 2011, monat: 7, geber: 'Robert Heim', werbung: 0.37807692, trinkgeld: 0.62179487, status: 'finanziert'},
		{'jahr': 2011, monat: 8, geber: 'Robert Heim', werbung: 0.30361539, trinkgeld: 0.69418168, status: 'finanziert'},
		{'jahr': 2011, monat: 9, geber: 'Robert Heim', werbung: 0.93068966, trinkgeld: 0.06896552, status: 'finanziert'},
		{'jahr': 2011, monat: 10, geber: 'Robert Heim', werbung: 0.77655172, trinkgeld: 0.20896552, status: 'finanziert'},
		{'jahr': 2011, monat: 11, geber: 'Robert Heim', werbung: 0.65103448, trinkgeld: 0.34862069, status: 'finanziert'},
		{'jahr': 2011, monat: 12, geber: 'Robert Heim', werbung: 0.60931034, trinkgeld: 0.39068966, status: 'finanziert'},
		{'jahr': 2012, monat: 1, geber: 'Robert Heim', werbung: 0.51655172, trinkgeld: 0.48344828, status: 'finanziert'},
		{'jahr': 2012, monat: 2, geber: 'Robert Heim', werbung: 0.71068966, trinkgeld: 0.28931034, status: 'finanziert'},
		{'jahr': 2012, monat: 3, geber: 'Robert Heim', werbung: 0.45966667, trinkgeld: 0.54033333, status: 'finanziert'},
		{'jahr': 2012, monat: 4, geber: 'Robert Heim', werbung: 0.38500000, trinkgeld: 0.61466667, status: 'finanziert'},
		{'jahr': 2012, monat: 5, geber: 'Robert Heim', werbung: 0.45113636, trinkgeld: 0.54403409, status: 'finanziert'},
		{'jahr': 2012, monat: 6, geber: 'Robert Heim', werbung: 0.61645161, trinkgeld: 0.38322581, status: 'finanziert'},
		{'jahr': 2012, monat: 7, geber: 'Robert Heim', werbung: 0.31290323, trinkgeld: 0.68612903, status: 'finanziert'},
		{'jahr': 2012, monat: 8, geber: 'Robert Heim', werbung: 0.11809754, trinkgeld: 0.86913879, status: 'finanziert'},
		{'jahr': 2012, monat: 9, geber: 'Robert Heim', werbung: 0.45806452, trinkgeld: 0.53225806, status: 'finanziert'},
		{'jahr': 2012, monat: 10, geber: 'Robert Heim', werbung: 0.52096774, trinkgeld: 0.47774194, status: 'finanziert'},
		{'jahr': 2012, monat: 11, geber: 'Robert Heim', werbung: 0.26419355, trinkgeld: 0.73387097, status: 'finanziert'},
		{'jahr': 2012, monat: 12, geber: 'Robert Heim', werbung: 0.34193548, trinkgeld: 0.65548387, status: 'finanziert'},
		{'jahr': 2013, monat: 1, geber: 'Robert Heim', werbung: 0.17129032, trinkgeld: 0.82677419, status: 'finanziert'},
		{'jahr': 2013, monat: 2, geber: 'Robert Heim', werbung: 0.03903226, trinkgeld: 0.95387097, status: 'finanziert'},
		{'jahr': 2013, monat: 3, geber: 'Robert Heim', werbung: 0.11677419, trinkgeld: 0.87838710, status: 'finanziert'},
		{'jahr': 2013, monat: 4, geber: 'Robert Heim', werbung: 0.09838710, trinkgeld: 0.87000000, status: 'finanziert'},
		{'jahr': 2013, monat: 5, geber: 'Robert Heim', werbung: 0.04903226, trinkgeld: 0.94774194, status: 'finanziert'},
		{'jahr': 2013, monat: 6, geber: 'Robert Heim', werbung: 0.10322581, trinkgeld: 0.89290323, status: 'finanziert'},
		{'jahr': 2013, monat: 7, geber: 'Robert Heim', werbung: 0.06612903, trinkgeld: 0.92935484, status: 'finanziert'},
		{'jahr': 2013, monat: 8, geber: 'Robert Heim', werbung: 0.02888620, trinkgeld: 0.97111380, status: 'finanziert'},
		{'jahr': 2013, monat: 9, geber: 'Robert Heim', werbung: 0.18129032, trinkgeld: 0.81548387, status: 'finanziert'},
		{'jahr': 2013, monat: 10, geber: 'Robert Heim', werbung: 0.26193548, trinkgeld: 0.73548387, status: 'finanziert'},
		{'jahr': 2013, monat: 11, geber: 'Robert Heim', werbung: 0.21903226, trinkgeld: 0.76483871, status: 'finanziert'},
		{'jahr': 2013, monat: 12, geber: 'Robert Heim', werbung: 0.12827763, trinkgeld: 0.86555270, status: 'finanziert'},
		{'jahr': 2014, monat: 1, geber: 'Robert Heim', werbung: 0.91139241, trinkgeld: 0.07848101, status: 'finanziert'},
		{'jahr': 2014, monat: 2, geber: 'Robert Heim', werbung: 0.45569620, trinkgeld: 0.52025316, status: 'finanziert'},
		{'jahr': 2014, monat: 3, geber: 'Robert Heim', werbung: 0.45696203, trinkgeld: 0.51772152, status: 'finanziert'},
		{'jahr': 2014, monat: 4, geber: 'Robert Heim', werbung: 0.36455696, trinkgeld: 0.60886076, status: 'finanziert'},
		{'jahr': 2014, monat: 5, geber: 'Robert Heim', werbung: 0.27215190, trinkgeld: 0.71265823, status: 'finanziert'},
		{'jahr': 2014, monat: 6, geber: 'Robert Heim', werbung: 0.07594937, trinkgeld: 0.89620253, status: 'finanziert'},
		{'jahr': 2014, monat: 7, geber: 'Robert Heim', werbung: 0.27974684, trinkgeld: 0.72025316, status: 'finanziert'},
		{'jahr': 2014, monat: 8, geber: '', werbung: 1.00000000, trinkgeld: 0.00000000, status: 'finanziert'},
		{'jahr': 2014, monat: 9, geber: '', werbung: 1.00000000, trinkgeld: 0.00000000, status: 'finanziert'},
		{'jahr': 2014, monat: 10, geber: '', werbung: 1.00000000, trinkgeld: 0.00000000, status: 'finanziert'},
		{'jahr': 2014, monat: 11, geber: '', werbung: 1.00000000, trinkgeld: 0.00000000, status: 'finanziert'},
		{'jahr': 2014, monat: 12, geber: '', werbung: 1.00000000, trinkgeld: 0.00000000, status: 'finanziert'},
		{'jahr': 2015, monat: 1, geber: '', werbung: 1.00000000, trinkgeld: 0.00000000, status: 'finanziert'},
		{'jahr': 2015, monat: 2, geber: '', werbung: 1.00000000, trinkgeld: 0.00000000, status: 'finanziert'},
		{'jahr': 2015, monat: 3, geber: '', werbung: 1.00000000, trinkgeld: 0.00000000, status: 'finanziert'},
		{'jahr': 2015, monat: 4, geber: 'Robert Heim, taichi1082', werbung: 0.60000000, trinkgeld: 0.40000000, status: 'finanziert'},
		{'jahr': 2015, monat: 5, geber: 'taichi1082, Mayu, ChainBreak', werbung: 0.00000000, trinkgeld: 1.00000000, status: 'finanziert'},
		{'jahr': 2015, monat: 6, geber: 'ChainBreak', werbung: 0.00000000, trinkgeld: 1.00000000, status: 'finanziert'},
		{'jahr': 2015, monat: 7, geber: 'ChainBreak, Huy aka Skiller', werbung: 0.00000000, trinkgeld: 1.00000000, status: 'finanziert'},
		{'jahr': 2015, monat: 8, geber: 'Huy aka Skiller', werbung: 0.00000000, trinkgeld: 1.00000000, status: 'finanziert'},
		{'jahr': 2015, monat: 9, geber: 'Huy aka Skiller', werbung: 0.00000000, trinkgeld: 1.00000000, status: 'finanziert'},
		{'jahr': 2015, monat: 10, geber: 'Huy aka Skiller, Syrti', werbung: 0.00000000, trinkgeld: 1.00000000, status: 'finanziert'},
		{'jahr': 2015, monat: 11, geber: 'Syrti, wazzabi, Yi-Quang', werbung: 0.00000000, trinkgeld: 1.00000000, status: 'finanziert'},
		{'jahr': 2015, monat: 12, geber: 'Yi-Quang, Vega', werbung: 0.00000000, trinkgeld: 1.00000000, status: 'finanziert'},
		{'jahr': 2016, monat: 1, geber: 'Vega', werbung: 0.00000000, trinkgeld: 1.00000000, status: 'finanziert'},
		{'jahr': 2016, monat: 2, geber: 'Vega, KerlPinselTreibholz', werbung: 0.00000000, trinkgeld: 1.00000000, status: 'finanziert'},
		{'jahr': 2016, monat: 3, geber: 'KerlPinselTreibholz, president', werbung: 0.00000000, trinkgeld: 1.00000000, status: 'finanziert'},
		{'jahr': 2016, monat: 4, geber: 'president, Comssa Penna, Sponge', werbung: 0.00000000, trinkgeld: 1.00000000, status: 'finanziert'},
		{'jahr': 2016, monat: 5, geber: 'Sponge', werbung: 0.00000000, trinkgeld: 0.36455696, status: '5.02 € offen'},
		{'jahr': 2016, monat: 6, geber: '', werbung: 0.00000000, trinkgeld: 0.00000000, status: '8.21 € offen'}
	];
	$scope.geber = [
		{name: 'Robert Heim', anzahl: 43, trinkgeld: 872.3699999999999},
		{name: 'Huy aka Skiller', anzahl: 1, trinkgeld: 25.0},
		{name: 'ChainBreak', anzahl: 1, trinkgeld: 16.0},
		{name: 'Yi-Quang', anzahl: 1, trinkgeld: 10.0},
		{name: 'Vega', anzahl: 1, trinkgeld: 10.0},
		{name: 'president', anzahl: 1, trinkgeld: 10.0},
		{name: 'KerlPinselTreibholz', anzahl: 1, trinkgeld: 8.0},
		{name: 'Syrti', anzahl: 1, trinkgeld: 5.0},
		{name: 'taichi1082', anzahl: 1, trinkgeld: 4.0},
		{name: 'Comssa Penna', anzahl: 2, trinkgeld: 4.0},
		{name: 'Sponge', anzahl: 1, trinkgeld: 4.0},
		{name: 'wazzabi', anzahl: 1, trinkgeld: 2.0},
		{name: 'Mayu', anzahl: 1, trinkgeld: 1.56}
	];
	$scope.standDatum = "03.11.2014";
};