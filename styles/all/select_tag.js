$(function(){
	$('.rh_topictags_suggestions').find('a').click(function(event){
		event.preventDefault();

		var $scope = angular.element($("#rhTopicTagsInputAppScope")).scope();
		var t = $(this).parent().text();
		$scope.$apply(function($scope) {
			$scope.addTag(t);
		});
	});
});
