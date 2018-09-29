<?php
/*

   <?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0"
   xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
   xsi:schemaLocation="urn:ietf:params:xml:ns:epp-1.0 epp-1.0.xsd">
<command xmlns="urn:ietf:params:xml:ns:epp-1.0">
 <update>
   <domain:update
     xmlns:domain="urn:ietf:params:xml:ns:domain-1.0"
     xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
     xsi:schemaLocation="urn:ietf:params:xml:ns:domain-1.0 domain-1.0.xsd"
   >
     <domain:name>EXAMPLE.COM</domain:name>



     <domain:chg>
       <domain:registrant>8013</domain:registrant>
       <domain:authInfo>
         <domain:pw>adsfdsf-asdf.112</domain:pw>
       </domain:authInfo>
     </domain:chg>
   </domain:update>
 </update>
 <extension>
   <namestoreExt:namestoreExt
     xmlns:namestoreExt="http://www.verisign-grs.com/epp/namestoreExt-1.1"
     xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
     xsi:schemaLocation="http://www.verisign-grs.com/epp/namestoreExt-1.1 namestoreExt-1.1.xsd"
   >
     <namestoreExt:subProduct>dotCOM</namestoreExt:subProduct>
   </namestoreExt:namestoreExt>
   <secDNS:update
     xmlns:secDNS="urn:ietf:params:xml:ns:secDNS-1.1"
     xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
     xsi:schemaLocation="urn:ietf:params:xml:ns:secDNS-1.1 secDNS-1.1.xsd"
   >
     <secDNS:rem>
       <secDNS:dsData>
         <secDNS:keyTag>5535</secDNS:keyTag>
         <secDNS:alg>5</secDNS:alg>
         <secDNS:digestType>1</secDNS:digestType>
         <secDNS:digest>5411674BFF957211D129B0DFE9410AF753559D4B</secDNS:digest>
       </secDNS:dsData>
     </secDNS:rem>
     <secDNS:add>
       <secDNS:dsData>
         <secDNS:keyTag>6475</secDNS:keyTag>
         <secDNS:alg>5</secDNS:alg>
         <secDNS:digestType>1</secDNS:digestType>
         <secDNS:digest>2411674BFF957211D129B0DFE9410AF753559D4B</secDNS:digest>
       </secDNS:dsData>
     </secDNS:add>
   </secDNS:update>
   <coa:update
     xmlns:coa="urn:ietf:params:xml:ns:coa-1.0"
     xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
     xsi:schemaLocation="urn:ietf:params:xml:ns:coa-1.0 coa-1.0.xsd"
   >
     <coa:rem>
       <coa:key>EXAMPLEREM-ID</coa:key>
       <coa:key>SDF</coa:key>
     </coa:rem>
     <coa:put>
       <coa:attr>
         <coa:key>EXAMPLE-ID</coa:key>
         <coa:value>12345</coa:value>
       </coa:attr>
       <coa:attr>
         <coa:key>SEF</coa:key>
         <coa:value>sfdsa12112</coa:value>
       </coa:attr>
     </coa:put>
   </coa:update>
   <relDom:update xmlns:relDom="http://www.verisign.com/epp/relatedDomain-1.0">
     <relDom:name>EXAMPLE.COM</relDom:name>
     <relDom:name>EXAMPLEq.COM</relDom:name>
   </relDom:update>
 </extension>
 <clTRID>ABC-82301-XYZ</clTRID>
</command>
</epp>

    */