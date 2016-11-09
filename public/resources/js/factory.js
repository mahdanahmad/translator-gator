app.factory('fetcher', ['$http', function($http) {
    var state   = '';
    var states  = [];

    return {
        getRandomState : function(callback) {
            $http.get('api/config/action').success(function(response) {
                if ((response.status_code == "200") && (response.response == "OK")) {
                    states = response.result;
                } else {
                    states = ['translate', 'alternative', 'vote', 'categorize'];
                }
            }).error(function(response) {
                states  = ['translate', 'alternative', 'vote', 'categorize'];
            }).then(function(response) {
                callback(_.sample(states));
            });
        },
        getConfig : function(callback) {
            $http.get('api/config').success(callback).error(callback);
        },
        getAction : function(callback) {
            $http.get('api/config/action').success(callback).error(callback);
        },
        getRedeemTime : function(callback) {
            $http.get('api/config/redeem').success(callback).error(callback);
        },
        putConfig : function(data, callback) {
            $http.put('api/config', data).success(callback).error(callback);
        },
        getLanguage : function(callback) {
            $http.get('api/languages').success(callback).error(callback);
        },
        postLanguage : function(data, callback) {
            $http.post('api/languages', data).success(callback).error(callback);
        },
        putLanguage : function(id, data, callback) {
            $http.put('api/languages/' + id, data).success(callback).error(callback);
        },
        deleteLanguage : function(id, callback) {
            $http.delete('api/languages/' + id).success(callback).error(callback);
        },
        getCategories : function(callback) {
            $http.get('api/categories').success(callback).error(callback);
        },
        postCategory : function(category_id, data, callback) {
            $http.post('api/categories/' + category_id + '/items', data).success(callback).error(callback);
        },
        putCategory : function(category_id, id, data, callback) {
            $http.put('api/categories/' + category_id + '/items/' + id, data).success(callback).error(callback);
        },
        deleteCategory : function(category_id, id, callback) {
            $http.delete('api/categories/' + category_id + '/items/' + id).success(callback).error(callback);
        },
        postCategoryGroup : function(data, callback) {
            $http.post('api/categories', data).success(callback).error(callback);
        },
        putCategoryGroup : function(id, data, callback) {
            $http.put('api/categories/' + id, data).success(callback).error(callback);
        },
        deleteCategoryGroup : function(id, callback) {
            $http.delete('api/categories/' + id).success(callback).error(callback);
        },
        postLogin : function(data, callback) {
            $http.post('api/users/login', data).success(callback).error(callback);
        },
        postRegister : function(data, callback) {
            $http.post('api/users', data).success(callback).error(callback);
        },
        getUser : function(id, callback) {
            $http.get('api/users/' + id).success(callback).error(callback);
        },
        putUser : function(id, data, callback) {
            $http.put('api/users/' + id, data).success(callback).error(callback);
        },
        getUserLanguage : function(id, callback) {
            $http.get('api/users/' + id + '/language').success(callback).error(callback);
        },
        getLeaderboard : function(callback) {
            $http.get('api/leaderboard').success(callback).error(callback);
        },
        postConfirm : function(data, callback) {
            $http.post('api/users/resetconfirmation', data).success(callback).error(callback);
        },
        getConfirm : function(data, callback) {
            $http.get('api/users/confirm/' + data).success(callback).error(callback);
        },
        postForgot : function(data, callback) {
            $http.post('api/users/resetpassword', data).success(callback).error(callback);
        },
        checkForgot : function(data, callback) {
            $http.get('api/users/forgot/' + data).success(callback).error(callback);
        },
        postNewPassword : function(data, callback) {
            $http.post('api/users/newpassword', data).success(callback).error(callback);
        },
        getTranslate : function(callback) {
            $http.get('api/untranslated').success(callback).error(callback);
        },
        postTranslate : function(data, callback) {
            $http.post('api/translatedwords', data).success(callback).error(callback);
        },
        getAlternative : function(id, callback) {
            $http.get('api/alternatewords/' + id).success(callback).error(callback);
        },
        postAlternative : function(data, callback) {
            $http.post('api/translatedwords', data).success(callback).error(callback);
        },
        getVote : function(id, callback) {
            $http.get('api/votewords/' + id).success(callback).error(callback);
        },
        postVote : function(data, callback) {
            $http.post('api/votewords', data).success(callback).error(callback);
        },
        getCategorize : function(id, callback) {
            $http.get('api/categorizewords/' + id).success(callback).error(callback);
        },
        postCategorize : function(data, callback) {
            $http.post('api/categorizewords', data).success(callback).error(callback);
        },
        postDebug : function(data, callback) {
            $http.post('api/debug', data, {
                transformRequest: angular.identity,
                headers: {'Content-Type': undefined}
            }).success(callback).error(callback);
        },
        getStatistic : function(callback) {
            $http.get('api/stats').success(callback).error(callback);
        },
        getCSV : function(data, callback) {
            $http.get('api/export?' + data).success(callback).error(callback);
        },
        postCSV : function(data, callback) {
            $http.post('api/originwords', data, {
                transformRequest: angular.identity,
                headers: {'Content-Type': undefined}
            }).success(callback).error(callback);
        },
        getRedeem : function(callback) {
            $http.get('api/redeem').success(callback).error(callback);
        },
        getUserRedeem : function(id, callback) {
            $http.get('api/redeem/' + id).success(callback).error(callback);
        },
        postRedeem : function(data, callback) {
            $http.post('api/redeem', data).success(callback).error(callback);
        },
        uploadRedeem : function(data, callback) {
            $http.post('api/redeem/upload', data, {
                transformRequest: angular.identity,
                headers: {'Content-Type': undefined}
            }).success(callback).error(callback);
        },

    };
}]);

app.factory('messageHelper', ['$http', function($http) {
    return {
        massiveErrorMsg : function() {
            return "Sorry, we've encounter some problem right now. Please refresh this page and hoping it will fix the problem.";
        },
        gainPointMsg    : function(value, action) {
            return "Whoops! Congratulation, you've got " + value + " points for " + action + ".";
        },
        noPointMsg      : function(action) {
            return "Too bad, you've got no points because you " + action + ".";
        },
        confirmMsg      : function() {
            return "Your account is already confirmed! Enjoy our games!"
        },
        forgotMsg      : function() {
            return "Your password is already changed."
        },
    };
}]);
