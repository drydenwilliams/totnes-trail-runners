require "susy"
#require 'autoprefixer-rails'
# Require any additional compass plugins here.


# Set this to the root of your project when deployed:
http_path = "/"
css_dir = "/"
sass_dir = "sass"
images_dir = "images"
javascripts_dir = "js"
cache_path = "/sass-cache"

# You can select your preferred output style here (can be overridden via the command line):
# output_style = :expanded or :nested or :compact or :compressed
output_style = :expanded

# To enable relative paths to assets via compass helper functions. Uncomment:
# relative_assets = true

# To disable debugging comments that display the original location of your selectors. Uncomment:
#line_comments = false

# If you prefer the indented syntax, you might want to regenerate this
# project again passing --syntax sass, or you can uncomment this:
# preferred_syntax = :sass
# and then run:
# sass-convert -R --from scss --to sass sass scss && rm -rf sass && mv scss sass

#debug
#sass_options = {:debug_info => true}

#sourcemaps
sass_options = {:sourcemap => true}
enable_sourcemaps = true


# Autoprefixer
# https://github.com/postcss/autoprefixer

# on_stylesheet_saved do |file|
#   css = File.read(file)
#   File.open(file, 'w') do |io|
#     io << AutoprefixerRails.process(css)
#   end
# end
