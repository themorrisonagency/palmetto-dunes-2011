module.exports = function (grunt) {
    grunt.initConfig({
        sass: {
            dist: {
                options: {       // Output options
                    style: 'compact'
                },
                files: [{
                    expand: true,
                    cwd: '../web/application/themes/theme_palmetto/css/inc/build',
                    src: ['layout.all.all.scss'],
                    dest: '../web/application/themes/theme_palmetto/css/inc',
                    ext: '.css'
                }]
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.registerTask('debug', ['sass']);
    grunt.registerTask('release', ['sass']);
    grunt.registerTask('css', ['sass']);
};
