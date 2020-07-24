module.exports = function (grunt) {

  const sass = require('node-sass');

  /* * Load Grunt Plugins * */
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-stylelint');
  grunt.loadNpmTasks('grunt-sass');
  grunt.loadNpmTasks('grunt-autoprefixer');
  grunt.loadNpmTasks('grunt-eslint');
  grunt.loadNpmTasks('grunt-browserify');
  grunt.loadNpmTasks('grunt-terser');
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-contrib-compress');
  grunt.loadNpmTasks('grunt-exec');

  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    eslint: {
      options: {
        configFile: '.eslintrc.json'
      },
      target: [
        'assets/public/src/js/**/*.js'
      ]
    },
    stylelint: {
      options: {
        configFile: 'stylelintrc.json',
        formatter: 'string',
        ignoreDisables: false,
        failOnError: true,
        outputFile: '',
        reportNeedlessDisables: false,
        syntax: 'scss'
      },
      src: [
        'assets/public/src/scss/**/*.{css,scss}'
      ]
    },
    sass: {
      dev: {
        options: {
          implementation: sass,
        },
        files: [
          {'assets/public/dist/css/public.min.css': ['assets/public/src/scss/public.scss']}
        ]
      },
      prod: {
        options: {
          implementation: sass,
          outputStyle: 'compressed',
        },
        files: [
          {'assets/public/dist/css/public.min.css': ['assets/public/src/scss/public.scss']}
        ]
      }
    },
    autoprefixer: {
      options: {
        browsers: ['last 2 versions']
      },
      dist: {
        options: {
          map: false
        },
        files: [
          {'assets/public/dist/css/public.min.css': 'assets/public/dist/css/public.min.css'}
        ]
      }
    },
    browserify: {
      dev: {
        files: [
          {'assets/public/dist/js/public.min.js': 'assets/public/src/js/public.js'},
        ],
        options: {
          transform: [
            [
              'babelify',
              {
                presets: [
                  ['@babel/preset-env',
                    {
                      targets: '> 0.25%, not dead',
                      useBuiltIns: 'usage',
                      'corejs': 3
                    }
                  ]
                ]
              }
            ]
          ],
          browserifyOptions: {
            debug: true
          }
        }
      },
      prod: {
        files: [
          {'assets/public/dist/js/public.min.js': 'assets/public/src/js/public.js'},
        ],
        options: {
          transform: [
            [
              'babelify',
              {
                presets: [
                  ['@babel/preset-env',
                    {
                      targets: '> 0.25%, not dead',
                      useBuiltIns: 'usage',
                      'corejs': 3
                    }
                  ]
                ]
              }
            ]
          ],
          browserifyOptions: {
            debug: false
          }
        }
      }
    },
    terser: {
      options: {},
      target: {
        files: {
          'assets/public/dist/js/public.min.js': ['assets/public/dist/js/public.min.js']
        }
      },
    },
    copy: {
      deploy: {
        files: [
          {
            expand: true,
            src: [
              'assets/public/dist/**',
              'languages/**',
              'src/**',
              'vendor/**',
              '*.php',
              '*.md',
            ],
            dest: 'build/et-simple-crm'
          }
        ]
      }
    },
    compress: {
      deploy: {
        options: {
          archive: 'et-simple-crm.zip'
        },
        expand: true,
        cwd: 'build/',
        src: ['**/*']
      }
    },
    clean: {
      deploy: {
        src: ["build"]
      }
    },
    exec: {
      composer_install: 'composer install --prefer-dist --no-dev',
      composer_dumpautoload: 'composer dump-autoload -o'
    },
    watch: {
      sass: {
        files: [
          'assets/public/src/scss/**/*.scss'
        ],
        tasks: ['stylelint', 'sass:dev', 'autoprefixer']
      },
      js: {
        files: [
          'assets/public/src/js/**/*.js',
        ],
        tasks: ['eslint', 'browserify:dev']
      }
    }
  });

  /* * Register Tasks * */
  grunt.registerTask('dev', [
    'stylelint',
    'sass:dev',
    'autoprefixer',
    'eslint',
    'browserify:dev'
  ]);

  grunt.registerTask('default', [
    'stylelint',
    'sass:prod',
    'autoprefixer',
    'eslint',
    'browserify:prod',
    'terser'
  ]);

  // * grunt build * triggers on every merge to master
  grunt.registerTask('build', [
    'exec:composer_install',
    'exec:composer_dumpautoload',
    'stylelint',
    'sass:prod',
    'autoprefixer',
    'eslint',
    'browserify:prod',
    'terser',
    'copy:deploy'
  ]);

  // * grunt deploy * triggers when releasing
  grunt.registerTask('deploy', [
    'compress',
    'clean:deploy'
  ]);
};
