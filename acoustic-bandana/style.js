
// this is a style from demo.html
var chicago_author_date = `
<?xml version="1.0" encoding="utf-8"?>
<style xmlns="http://purl.org/net/xbiblio/csl" class="in-text" version="1.0" et-al-min="4" et-al-use-first="3" et-al-subsequent-min="3" et-al-subsequent-use-first="2" initialize-with-hyphen="false" demote-non-dropping-particle="sort-only" default-locale="en-GB">
  <info>
    <title>Biological Journal of the Linnean Society</title>
    <title-short>BJLS</title-short>
    <id>http://www.zotero.org/styles/biological-journal-of-the-linnean-society</id>
    <link href="http://www.zotero.org/styles/biological-journal-of-the-linnean-society" rel="self"/>
    <link href="http://onlinelibrary.wiley.com/journal/10.1111/(ISSN)1095-8312/homepage/ForAuthors.html" rel="documentation"/>
    <author>
      <name>Iris Starnberger</name>
      <email>iris.starnberger@univie.ac.at</email>
    </author>
    <category citation-format="author-date"/>
    <category field="biology"/>
    <issn>0024-4066</issn>
    <eissn>1095-8312</eissn>
    <updated>2013-06-26T10:04:07+00:00</updated>
    <rights license="http://creativecommons.org/licenses/by-sa/3.0/">This work is licensed under a Creative Commons Attribution-ShareAlike 3.0 License</rights>
  </info>
  <macro name="editor-translator">
    <names variable="editor translator" prefix="(" suffix=")" delimiter=", ">
      <name and="text" initialize-with="" delimiter=", "/>
      <et-al font-style="italic"/>
      <label form="short" prefix=", " text-case="capitalize-first" suffix="." strip-periods="true"/>
    </names>
  </macro>
  <macro name="author">
    <names variable="author">
      <name name-as-sort-order="all" sort-separator=" " initialize-with="" delimiter=", " delimiter-precedes-last="always" font-weight="bold"/>
      <et-al font-style="italic"/>
      <label form="short" prefix=" (" suffix=".)" text-case="capitalize-first" strip-periods="true"/>
      <substitute>
        <names variable="editor"/>
        <names variable="translator"/>
        <text macro="title"/>
      </substitute>
    </names>
  </macro>
  <macro name="author-short">
    <names variable="author">
      <name form="short" and="symbol" et-al-min="4" et-al-use-first="1" et-al-subsequent-min="3" et-al-subsequent-use-first="1" initialize-with="."/>
      <et-al font-style="italic"/>
      <substitute>
        <names variable="editor"/>
        <names variable="translator"/>
        <choose>
          <if type="bill book graphic legal_case legislation motion_picture report song" match="any">
            <text variable="title" form="short" font-style="italic"/>
          </if>
          <else>
            <text variable="title" form="short" quotes="true"/>
          </else>
        </choose>
      </substitute>
    </names>
  </macro>
  <macro name="title">
    <choose>
      <if type="bill book graphic legal_case legislation motion_picture report song" match="any">
        <text variable="title" font-style="italic"/>
      </if>
      <else>
        <text variable="title"/>
      </else>
    </choose>
  </macro>
  <macro name="publisher">
    <group delimiter=": ">
      <text variable="publisher-place"/>
      <text variable="publisher"/>
    </group>
  </macro>
  <!-- access such as DOI, URL, etc. -->
  <macro name="access">
				<choose>
					<if variable="DOI">
						<text variable="DOI" prefix="doi:" suffix=".">
						</text>
					</if>  
					<else-if variable="CINII">
						<text variable="CINII" prefix="cinii:" suffix=".">
						</text>
					</else-if>  
					<else-if variable="HANDLE">
						<text variable="HANDLE" prefix="hdl:" suffix=".">
						</text>
					</else-if> 
					<else-if variable="ZOOBANK">
						<text variable="ZOOBANK" prefix="zoobank:" suffix=".">
						</text>
					</else-if> 
				</choose>
</macro>
  <citation collapse="year-suffix" et-al-min="3" et-al-use-first="1" et-al-subsequent-min="3" et-al-subsequent-use-first="1" disambiguate-add-year-suffix="true" year-suffix-delimiter=",">
    <sort>
      <key variable="issued"/>
      <key variable="author"/>
    </sort>
    <layout prefix="(" suffix=")" delimiter="; ">
      <group delimiter=", ">
        <text macro="author-short" text-case="capitalize-first"/>
        <date variable="issued">
          <date-part name="year"/>
        </date>
        <group>
          <label variable="locator" suffix="." form="short" strip-periods="true"/>
          <text variable="locator" prefix=" "/>
        </group>
      </group>
    </layout>
  </citation>
  <bibliography et-al-min="10" et-al-use-first="10" hanging-indent="false">
    <sort>
      <key macro="author-short"/>
      <key variable="issued"/>
    </sort>
    <layout>
      <text macro="author" text-case="capitalize-first" font-weight="bold" suffix="."/>
      <date delimiter="" variable="issued" text-case="uppercase" font-weight="bold" prefix=" " suffix=".">
        <date-part name="year"/>
      </date>
      <choose>
        <if type="bill book graphic legal_case legislation motion_picture report song" match="any">
          <group suffix=".">
            <text macro="title" prefix=" "/>
            <text macro="editor-translator" prefix=" "/>
          </group>
          <text prefix=" " suffix="." macro="publisher"/>
        </if>
        <else-if type="chapter paper-conference" match="any">
          <text macro="title" prefix=" "/>
          <group prefix=".">
            <group prefix="" suffix="">
              <group suffix=".">
                <names variable="editor translator">
                  <name name-as-sort-order="all" sort-separator=" " initialize-with="" delimiter=", " delimiter-precedes-last="always" prefix=" In: " suffix=","/>
                  <label form="short" prefix=" " suffix="." strip-periods="true"/>
                </names>
                <text variable="collection-title" prefix=" " suffix="."/>
                <text variable="container-title" prefix=" " suffix="."/>
                <text macro="publisher" prefix=" " suffix=", "/>
                <text variable="page" suffix="."/>
              </group>
            </group>
          </group>
        </else-if>
        <else>
          <group suffix=".">
            <text macro="title" prefix=" "/>
            <text macro="editor-translator" prefix=" "/>
          </group>
          <group prefix=" " suffix=".">
            <text variable="container-title" font-style="italic"/>

            <text variable="collection-title" prefix=" (" suffix=")"/>

            <group prefix=" ">
              <text variable="volume" font-weight="bold"/>
              <text variable="issue" prefix="(" suffix=")"/>
            </group>
            <text variable="page" prefix=": "/>
          </group>
        </else>
      </choose>
      
	<!-- DOI, URL -->
	<text macro="access" prefix=" ">
	</text>
      
      
    </layout>
  </bibliography>
</style>
`;

	