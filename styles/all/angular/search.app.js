$.fn.flash = function(duration, iterations) {
    duration = duration || 1000; // Default to 1 second
    iterations = iterations || 1; // Default to 1 iteration
    var iterationDuration = Math.floor(duration / iterations);

    for (var i = 0; i < iterations; i++) {
        this.fadeOut(iterationDuration).fadeIn(iterationDuration);
    }
    return this;
};

angular.module('searchApp', ['ngTagsInput'])
	.config(function($interpolateProvider, $httpProvider) {
		$interpolateProvider.startSymbol('{[{').endSymbol('}]}');
		$httpProvider.defaults.headers.common["X-Requested-With"] = 'XMLHttpRequest';
	})
	.controller('searchCtrl', function($scope, $http) {
		$scope.tags = [];
		$scope.tutorials = [];
		$scope.init = function (initTags, initTutorials) {
			if ('' !== initTags) {
				initTags = JSON.parse(atob(initTags));
				for (var i = 0; i < initTags.length; i++) {
					this.tags.push(initTags[i]);
				}
			}
			if ('' !== initTutorials) {
				initTutorials = JSON.parse(atob(initTutorials));
				for (var i = 0; i < initTutorials.length; i++) {
					this.tutorials.push(initTutorials[i]);
				}
			}
		};
		$scope.getSearchLink = function (baseUrl) {
			var terms = [];
			for (var i = 0; i < this.tags.length; i++) {
				terms.push(encodeURIComponent(this.tags[i].text));
			}
			/* baseUrl might contain a query string, e.g. `xxxx?sid=xxxxx` and rather than building a
		     `xxxx?sid=xxxxx/term1,term2` we need to build `xxxx/term1,term2?sid=xxxxx` */
			var base = baseUrl.split("?");
			var queryStr = base.length > 1 ? ('?' + base[1]) : '';
			var link = base[0];
			if (terms.length>0) {
				link += '/' + terms.join(',');
			}
			return link + queryStr;
		};
		$scope.loadTags = function(query) {
			var data = {
				'query': query,
				'exclude' : $scope.tags.map(function(tag) {
					return tag.text;
				})
			};
			return $http.post('/tags/suggest', data);
		};
		$scope.addTag = function(tag) {
			var found = false;
			this.tags.every(function(element, index, array) {
				if (element.text === tag) {
					found = true;
					return false;
				}
				return true;
			});
			if (!found) {
				this.tags.push({"text": tag});
			} else {
				$("span:contains('"+tag+"')")
				.filter(function() {
				    return $(this).text() === tag;
				})
				.parent()
				.flash(200, 3);
			}
		}
	});
