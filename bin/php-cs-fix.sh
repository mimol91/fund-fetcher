#!/bin/bash

./bin/php-cs-fixer fix src --level=symfony 
--fixers=-phpdoc_params,-concat_without_spaces,multiline_array_trailing_comma,namespace_no_leading_whitespace,new_with_braces,operators_spaces,remove_lines_between_uses,single_array_no_trailing_comma,spaces_cast,whitespacy_lines,yoda_conditions,concat_with_spaces,ordered_use,short_array_syntax
