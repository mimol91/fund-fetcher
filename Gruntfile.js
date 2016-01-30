var path = require('path');
var fs = require('fs');
var browserify = require('browserify');
var html = require('html-browserify');
var exec = require('shelljs').exec;
var find = require('findit');
var buildify = require('buildify');

var base = function(p) { return path.join(__dirname, p); };
var now = (new Date()).getTime();

module.exports = function(g) {
    require('time-grunt')(g);
    g.initConfig({
        pkg: g.file.readJSON('package.json'),

        clean: {
            vendor: {
                src: [
                    'web/build/vendor.js',
                    'web/build/vendor.min.js'
                ]
            },
            app: {
                src: [
                    'web/build/app.js',
                    'web/build/app.min.js',
                    'web/build/style.css',
                    'web/build/style.min.css'
                ]
            },
            bower: {
                src: ['bower_components']
            }
        },
        concat: {
            options: { separator: ';' },
            vendor: {
                src: [
                    'bower_components/angular/angular.js',
                    'bower_components/angular-ui-router/release/angular-ui-router.js',
                    'bower_components/jquery/dist/jquery.js',
                    'bower_components/bootstrap/js/bootstrap.js',
                    'bower_components/lodash/lodash.js',
                    'bower_components/highcharts/highstock.js'
                ],
                dest: 'web/build/vendor.js'
            },
            css: {
                src: [
                    'bower_components/bootstrap/dist/css/bootstrap.css'
                ],
                dest: 'web/build/vendor.css'
            }
        },
        uglify: {
            vendor: {
                files: {
                    'web/build/vendor.min.js': ['web/build/vendor.js']
                }
            },
            app: {
                options: { mangle: false },
                files: {
                    'web/build/app.min.js': ['web/build/app.js']
                }
            }
        },

        cssmin: {
            app: {
                files: {
                    'web/build/style.min.css': ['web/build/style.css']
                }
            },
            vendor: {
                files: {
                    'web/build/vendor.min.css': ['web/build/vendor.css']
                }
            }
        },

        jshint: {
            app: {
                options: { jshintrc: true },
                src: [
                    'Gruntfile.js',
                    'web/app/**/*.js'
                ]
            }
        },

        replace: {
            dev: {
                src: 'app/Resources/views/base.html',
                dest: 'web/index.html',
                replacements: [
                    { from: '{{MIN}}', to: '' },
                    { from: '{{MODE}}', to: 'dev' },
                    { from: '{{NOW}}', to: now },
                    { from: '{{RELOAD}}', to: '<script src="//localhost:35729/livereload.js"></script>' }
                ]
            },
            prod: {
                src: 'app/Resources/views/base.html',
                dest: 'web/index.html',
                replacements: [
                    { from: '{{MIN}}', to: '.min' },
                    { from: '{{MODE}}', to: 'prod' },
                    { from: '{{NOW}}', to: now },
                    { from: '{{RELOAD}}', to: '' }
                ]
            },
            map: {
                src: ['web/build/vendor.js'],
                overwrite: true,
                replacements: [
                    { from: /\/\/.*sourceMappingURL.*/i, to: '' }
                ]
            }
        },
        watch: {
            rebuild: {
                tasks: ['build-dev'],
                options: { livereload: true },
                files: [
                    'web/app/**/*.js',
                    'web/app/**/*.html',
                    'web/app/**/*.css'
                ]
            }
        }
    });

    g.loadNpmTasks('grunt-contrib-clean');
    g.loadNpmTasks('grunt-contrib-concat');
    g.loadNpmTasks('grunt-contrib-jshint');
    g.loadNpmTasks('grunt-contrib-uglify');
    g.loadNpmTasks('grunt-contrib-cssmin');
    g.loadNpmTasks('grunt-contrib-watch');
    g.loadNpmTasks('grunt-text-replace');
    g.loadNpmTasks('grunt-newer');

    g.registerTask('browserify', function() {
        var b = browserify();
        var done = this.async();

        b.add(base('web/app/app.js'));
        b.transform(html);
        b.bundle(function(err, buf) {
            if (err) { throw err; }
            fs.writeFileSync(base('web/build/app.js'), buf);
            done();
        });
    });

    g.registerTask('style', function() {
        var files = [];
        var finder = find(base('web/app'));
        var done = this.async();

        finder.on('file', function(f) { if (/\.css$/i.test(f)) { files.push(f); } });
        finder.on('error', function(err) { throw err; });
        finder.on('end', function() {
            buildify('/').concat(files).save(base('web/build/style.css'));
            done();
        });
    });

    g.registerTask('bower', function() {
        var bowerExecutable = base('node_modules/bower/bin/bower');

        exec(bowerExecutable + ' install');
    });

    g.registerTask('lint', [
        'newer:jshint:app'
    ]);


    g.registerTask('vendor', [
        'clean:bower',
        'bower',
        'concat:vendor',
        'concat:css',
        'replace:map',
        'uglify:vendor'
    ]);

    g.registerTask('build', [
        'clean:app',
        'lint',
        'browserify',
        'style'
    ]);

    g.registerTask('build-prod', [
        'build',
        'replace:prod',
        'uglify:app',
        'cssmin:vendor',
        'cssmin:app'
    ]);

    g.registerTask('build-dev', [
        'build',
        'replace:dev'
    ]);

    g.registerTask('postinstall', [
        'vendor',
        'build-prod'
    ]);

    g.registerTask('default', [
        'build-dev',
        'watch:rebuild'
    ]);
};