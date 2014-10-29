/**
 * Created by sparx on 29/10/14.
 */

module.exports = function(grunt) {

    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        jsdirs: {
            src: 'frontend/src',
            build: 'build',
            dest: 'public/js'
        },
        clean: {
            build: {
                src: ['build']
            },
            bower: {
                src: ['frontend/bower_components']
            },
            dest: {
                src: ['public/js']
            },
            css: {
                src: ['public/css']
            }
        },
        "bower-install-simple": {
            options: {
                color: true,
                directory: './frontend/bower_components'
            },
            "prod": {
                options: {
                    production: true
                }
            },
            "dev": {
                options: {
                    production: false
                }
            }
        },
        uglify: {
            options: {
                banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
            },
            dist: {
                files : {
                    '<%= jsdirs.build %>/base.js' : '<%= jsdirs.src %>/base.js',
                    '<%= jsdirs.build %>/blog.js' : '<%= jsdirs.src %>/sites/blog.js',
                    '<%= jsdirs.build %>/form.plugin.js' : '<%= jsdirs.src %>/plugins/form.plugin.js'
                }
            },
            vendor: {
                files : {
                    'frontend/bower_components/jquery.cookie/jquery.cookie.min.js' : 'frontend/bower_components/jquery.cookie/jquery.cookie.js'
                }
            }
        },
        bowercopy: {
            options: {
                srcPrefix: 'frontend/bower_components',
                runBower: false
            },
            vendorpackage: {
                options: {
                    destPrefix: 'build/vendor'
                },
                files: {
                    'bootstrap.min.js': 'bootstrap/dist/js/bootstrap.min.js',
                    'jquery.min.js': 'jquery/dist/jquery.min.js',
                    'jquery.cookie.min.js': 'jquery.cookie/jquery.cookie.min.js'
                }
            },
            jquerymap: {
                options: {
                    destPrefix: 'public/js'
                },
                files: {
                    'jquery.min.map': 'jquery/dist/jquery.min.map'
                }
            },
            iecompat: {
                options: {
                    destPrefix: 'public/js/vendor'
                },
                files: {
                    'html5shiv.js': 'html5shiv/dist/html5shiv.min.js',
                    'respond.js': 'respond/dest/respond.min.js',
                    'jquery.min.map': 'jquery/dist/jquery.min.map'
                }
            },
            vendorcss: {
                options: {
                    destPrefix: 'public/css'
                },
                files: {
                    'bootstrap.min.css': 'bootstrap/dist/css/bootstrap.min.css'
                }
            },
            vendorfonts: {
                options: {
                    destPrefix: 'public/fonts'
                },
                files: {
                    'glyphicons-halflings-regular.eot': 'bootstrap/dist/fonts/glyphicons-halflings-regular.eot',
                    'glyphicons-halflings-regular.svg': 'bootstrap/dist/fonts/glyphicons-halflings-regular.svg',
                    'glyphicons-halflings-regular.ttf': 'bootstrap/dist/fonts/glyphicons-halflings-regular.ttf',
                    'glyphicons-halflings-regular.woff': 'bootstrap/dist/fonts/glyphicons-halflings-regular.woff'
                }
            },
            css: {
                options: {
                    destPrefix: 'public/css'
                },
                files: {
                    'style.css': '../css/style.css'
                }
            }
        },
        concat: {
            options: {
                separator: '\n;\n'
            },
            dist: {
                src: [
                    '<%= jsdirs.build %>/base.js',
                    '<%= jsdirs.build %>/form.plugin.js',
                    '<%= jsdirs.build %>/blog.js'
                ],
                dest: '<%= jsdirs.dest %>/<%= pkg.name %>.min.js'
            },
            vendor: {
                src: [
                    '<%= jsdirs.build %>/vendor/jquery.min.js',
                    '<%= jsdirs.build %>/vendor/jquery.cookie.min.js',
                    '<%= jsdirs.build %>/vendor/bootstrap.min.js'
                ],
                dest: '<%= jsdirs.dest %>/vendorpackage.min.js'
            }
        }
    });

    // Load the plugin that provides the "uglify" task.
    grunt.loadNpmTasks("grunt-contrib-clean");
    grunt.loadNpmTasks("grunt-bower-install-simple");
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-bowercopy');

    // clean up all directories
    grunt.registerTask('cleanup', ['clean']);

    // install bower components
    grunt.registerTask('install', ['bower-install-simple']);

    // copy css stuff
    grunt.registerTask('css', ['bowercopy:css']);

    // copy bootstrap css and fonts
    grunt.registerTask('vendorcsscopy', ['bowercopy:vendorcss', 'bowercopy:vendorfonts']);

    // copy vendor js files
    grunt.registerTask('vendorjscopy', ['bowercopy:vendorpackage', 'bowercopy:jquerymap', 'bowercopy:iecompat']);

    // install concat and copy vendor js files
    grunt.registerTask('vendor', ['install', 'uglify:vendor', 'vendorjscopy', 'concat:vendor', 'vendorcsscopy']);

    // uglify project js files and concat them
    grunt.registerTask('build', ['uglify:dist', 'concat:dist', 'css']);

    // do a complete install
    grunt.registerTask('release', ['cleanup', 'vendor', 'build']);

    // Default task(s).
    grunt.registerTask('default', ['build']);

};