const path = require('path');

module.exports = {
    resolve: {
        alias: {
            '@': path.resolve('resources/js'),
            '@t': path.resolve('resources/js/tenants'),
            '@l': path.resolve('resources/js/landlord'),
        },
    },
};
