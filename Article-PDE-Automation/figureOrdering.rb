
def createFigureList(input)
  figures = [] ;
  input.each { 
    | line |
    case line 
    when /Fig (\d*)(<.*$)/ 
      puts "Found Figure: #{$1}"
      figures << $1.to_i
    when /Fig.[^\s]/
       puts "WARNING: No space after figure reference: " + line ;  
    end
  }
  return figures ;
end

def createMap(figures) 
  map = Hash.new ;
   (1..figures.length).each {
    | i |
    map[figures[i-1]] = i;      
  }
  return map ;
end

def replace(input, map, output)
  replacedReferences = [] 
  input.each { 
    | line |
    output.write line.gsub(/(:?Fig(:?\.)?) (\d*)/) {
      | s |
      replacedReferences << $3.to_i if $2 == "."    ;
      "Fig#{$2} #{map[$3.to_i]}"
    }
  }
  unreplacedReferences = map.keys.sort ;
  unreplacedReferences.delete_if { | ref | replacedReferences.include?(ref) }
  puts "Unreplaced References: " 
  puts unreplacedReferences ;
end 

input = File.open("article.html") 
figures = createFigureList(input) ;
map = createMap(figures)
map.keys.sort.each { |k| puts "#{k} => #{map[k]}" }
$stdout.sync = true
#puts "Replace (y/n)?" ;
#gets.chomp
input.rewind
replace(input, map, File.new("article_replaced.html", "w")) ;

