'use strict';

/*
 * @requires $http (Ajax Request)
 * @requires $q (Promise)
 * @requires config (Config Global for app)
 */
angular.module('app').factory('api', function ($http, $q, config, overlay) {

    return {
        /*
         * @name Generate Single questions
         * @param string sexe
         */
        single: function (sexe) {

            sexe = sexe || 'mal';

            overlay.set(true);

            var deferred = $q.defer();

            $http({
                method: 'GET',
                url: config.apiUrl + '/blind/generate/single/' + sexe
            })
                .success(function (result) {
                    overlay.set(false);
                    deferred.resolve(result);
                })
                .error(function (result) {
                    overlay.set(false);
                    deferred.resolve('Erreur :' + result);
                });

            return deferred.promise;

        },

        /*
         * @name Generate Number Questions with Category
         * @param string category
         * @param int number
         */
        multi: function (category, number) {

            category = category || 'geek';
            number = number || 1;

            overlay.set(true);

            var deferred = $q.defer();

            $http({
                method: 'GET',
                url: config.apiUrl + '/blind/generate/multi/' + category + '/' + number
            })
                .success(function (result) {
                    overlay.set(false);
                    deferred.resolve(result);
                })
                .error(function (result) {
                    overlay.set(false);
                    deferred.resolve('Erreur :' + result);
                });

            return deferred.promise;

        },

        /*
         * @name Create User
         * @param object params
         */
        userRegister: function (params) {

            var params_api = {
                facebook_id: params.id,
                firstname: params.first_name,
                lastname: params.last_name,
                picture: params.photo,
                sex: params.gender
            };

            overlay.set(true);

            var transform = function (data) {
                return $.param(data);
            }

            var deferred = $q.defer();

            $http({
                method: 'POST',
                url: config.apiUrl + '/user/register',
                data: params_api,
                headers: {'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'},
                transformRequest: transform
            })
                .success(function (result) {
                    overlay.set(false);
                    deferred.resolve(result);
                })
                .error(function (result) {
                    overlay.set(false);
                    deferred.resolve('Erreur :' + result);
                });

            return deferred.promise;
        },

        /*
         * @name Start a blind and add it in API
         * @param string type
         * @param int userId
         * @param int friendId
         */
        blindStart: function (type, userId, friendId) {

            var param = {
                    type: type || 'single',
                    user_id: userId || null,
                    friend_id: friendId || ''
                },
                transform = function (data) {
                    return $.param(data);
                };

            overlay.set(true);

            var deferred = $q.defer();

            $http({
                method: 'POST',
                url: config.apiUrl + '/blind/start',
                data: param,
                headers: {'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'},
                transformRequest: transform
            })
                .success(function (result) {
                    overlay.set(false);
                    deferred.resolve(result);
                })
                .error(function (result) {
                    overlay.set(false);
                    deferred.reject('Erreur :' + result);
                });

            return deferred.promise;
        },

        /*
         * @name Add in API the response choose
         * @param int blindId
         * @param int questionId
         * @param int|null responseId
         */
        blindResponse: function (blindId, questionId, responseId) {

            var param = {
                    blind_id: blindId,
                    question_id: questionId,
                    response_id: responseId || 0,
                },
                transform = function (data) {
                    return $.param(data);
                };

            overlay.set(true);

            $http({
                method: 'POST',
                url: config.apiUrl + '/blind/response',
                data: param,
                headers: {'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'},
                transformRequest: transform
            })
                .success(function (result) {
                    console.log(result);
                    overlay.set(false);
                    return true;
                })
                .error(function (result) {
                    console.log(result);
                    overlay.set(false);
                    return false;
                });

        },

        /*
         * @name Check if user exist in Way API
         * @param int facebookId
         */
        userExist: function (facebookId) {

            facebookId = facebookId || 1;

            overlay.set(true);

            var deferred = $q.defer();

            $http({
                method: 'GET',
                url: config.apiUrl + '/user/exist/' + facebookId
            })
                .success(function (result) {
                    overlay.set(false);
                    deferred.resolve(result);
                })
                .error(function (result) {
                    overlay.set(false);
                    deferred.resolve('Erreur :' + result);
                });

            return deferred.promise;
        },

        /*
         * @name Gets Notifications Users
         * @param int facebookId
         */
        getNotifications: function (facebookId) {

            facebookId = facebookId || 1;

            var deferred = $q.defer();

            $http({
                method: 'GET',
                url: config.apiUrl + '/notification/get/' + facebookId
            })
                .success(function (result) {
                    deferred.resolve(result);
                })
                .error(function (result) {
                    deferred.resolve('Erreur :' + result);
                });

            return deferred.promise;
        },

        /*
         * @name Gets Users informations
         * @param int facebookId
         */
        getUser: function (facebookId) {

            facebookId = facebookId || 1;

            overlay.set(true);

            var deferred = $q.defer();

            $http({
                method: 'GET',
                url: config.apiUrl + '/user/get/' + facebookId
            })
                .success(function (result) {
                    overlay.set(false);
                    deferred.resolve(result);
                })
                .error(function (result) {
                    overlay.set(false);
                    deferred.resolve('Erreur :' + result);
                });

            return deferred.promise;
        },

        removeNotification: function (userId, friendId) {
            // Do stuff ...
        },

        blindUpdate: function (blindId, status) {

            var param = {
                    id: blindId,
                    status: status
                },
                transform = function (data) {
                    return $.param(data);
                };

            overlay.set(true);

            $http({
                method: 'POST',
                url: config.apiUrl + '/blind/update',
                data: param,
                headers: {'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'},
                transformRequest: transform
            })
                .success(function () {
                    overlay.set(false);
                    return true;
                })
                .error(function () {
                    overlay.set(false);
                    return false;
                });

        }
    };

});