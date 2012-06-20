/***********************************
* Copyright 2008 NP QUIZ TEST 1.0  
* Site: Kiemtra.plus.vn            
************************************/
var notV="vb_login_password,navbar_password"
var fID="htmlbox"
var method=0
var on_off=1
var dockspell=1
var dauCu=true
var useCookie=1
var radioID=new Array("np_vnkey_auto","np_vnkey_telex","np_vnkey_vni","np_vnkey_viqr","np_vnkey_off","np_vnkey_ckspell")
var agt=navigator.userAgent.toLowerCase(),alphabet="QWERTYUIOPASDFGHJKLZXCVBNM\ ",them,spellerr,setCookie,getCookie
var is_ie=((agt.indexOf("msie")!=-1) && (agt.indexOf("opera")==-1)),S,F,J,R,X,D,oc,sk,saveStr,iwindow,frame,is_opera=false
var ver="",support=true,changed=false,uni,uni2,g,h,SFJRX,DAWEO,Z,AEO,moc,trang,kl=0,tw5
var skey=new Array(97,226,259,101,234,105,111,244,417,117,432,121,65,194,258,69,202,73,79,212,416,85,431,89)
var skey2="a,a,a,e,e,i,o,o,o,u,u,y,A,A,A,E,E,I,O,O,O,U,U,Y".split(','),A,E,O
var english=fcc(272)+fcc(194)+fcc(258)+fcc(416)+fcc(431)+fcc(202)+fcc(212),ds1=new Array('d','D'),db1=new Array(273,272)
var os1=new Array('o','O',417,416,243,211,242,210,7885,7884,7887,7886,245,213,7898,7900,7906,7902,7904)
var ob1=new Array(244,212,244,212,7889,7888,7891,7890,7897,7896,7893,7892,7895,7894,7888,7890,7896,7892,7894)
var mocs1=new Array('o','O',244,212,'u','U',243,211,242,210,7885,7884,7887,7886,245,213,250,218,249,217,7909,7908,7911,7910,361,360,7888, 7890,7896,7892,7894)
var mocb1=new Array(417,416,417,416,432,431,7899,7898,7901,7900,7907,7906,7903,7902,7905,7904,7913, 7912,7915,7914,7921,7920,7917,7916,7919,7918,7898, 7900,7906,7902,7904)
var trangs1=new Array('a','A',226,194,225,193,224,192,7841,7840,7843,7842,227,195,7844,7846,7852,7848,7850)
var trangb1=new Array(259,258,259,258,7855,7854,7857,7856,7863,7862,7859,7858, 7861,7860,7854,7856,7862,7858,7860)
var as1=new Array('a','A',259,258,225,193,224,192,7841,7840,7843,7842,227,195, 7854,7856,7862,7858,7860,7870,7872,7878,7874,7876)
var ab1=new Array(226,194,226,194,7845,7844,7847,7846,7853,7852,7849,7848,7851,7850,7844,7846,7852,7848,7850,201,200,7864,7866,7868)
var es1=new Array('e','E',233,201,232,200,7865,7864,7867,7866,7869,7868),eb1=new Array(234,202,7871,7870,7873,7872,7879,7878, 7875,7874,7877,7876)
function notWord(word) {
    var str="\ \r\n#,\\;.:-_()<>+-*/=?!\"$%{}[]\'~|^\@\&\t"+fcc(160)
    return(str.indexOf(word)>=0)
}
function mozGetText(obj) {
    var v=obj.value,pos,w="";g=1
    if(v.length<=0) return false
    if (!obj.data) {
        if (!obj.setSelectionRange) return false
        pos=obj.selectionStart
    } else pos=obj.pos
    while(1) {
        if(pos-g<0) break
        else if(notWord(v.substr(pos-g,1))) { if(v.substr(pos-g,1)=="\\") w=v.substr(pos-g,1)+w; break }
        else w=v.substr(pos-g,1)+w; g++
    }
    return new Array(w,pos)
}
function start(obj,key) {
    var w=""; oc=obj; uni2=false
    if(method==0) { uni="D,A,E,O,W,W".split(','); uni2="9,6,6,6,7,8".split(',') }
    else if(method==1) uni="D,A,E,O,W,W".split(',')
    else if(method==2) uni="9,6,6,6,7,8".split(',')
    else if(method==3) uni="D,^,^,^,+,(".split(',')
    if(!is_ie) {
        key=fcc(key.which)
        w=mozGetText(obj)
        if(!w) return
        main(w[0],key,w[1],uni)
        if(!dockspell) w=mozGetText(obj)
        if(!w) return
        if((uni2)&&(!changed)) main(w[0],key,w[1],uni2)
    } else {
        obj=ie_getText(obj)
        if(obj) {
        var sT=obj.curword.text
        w=main(obj.curword.text,key,0,uni)
        if((uni2)&&((w==sT)||(typeof(w)=='undefined'))) w=main(obj.curword.text,key,0,uni2)
        if(w) obj.curword.text=w
        }
    }
}
function ie_getText(obj) {
    var caret=obj.document.selection.createRange(),w=""
    if(caret.text) caret.text=""
    while(1) {
        caret.moveStart("character",-1)
        if(w.length==caret.text.length) break
        w=caret.text
        if(notWord(w.charAt(0))) {
        if(w.charCodeAt(0)==13) w=w.substr(2); else if(w.charAt(0)!="\\") w=w.substr(1)
        break
        }
    }
    if(w.length) {
        caret.collapse(false)
        caret.moveStart("character",-w.length)
        obj.curword=caret.duplicate()
        return obj
    } else return false
}
function ie_replaceChar(w,pos,c) {
    var r=""
    if(((c==417)||(c==416)) && (w.substr(w.length-pos-1,1).toUpperCase()=='U') && (pos!=1)&&(w.substr(w.length-pos-2,1).toUpperCase()!='Q')) {
        if (w.substr(w.length-pos-1,1)=='u') r=fcc(432); else r=fcc(431)
    }
    if(!isNaN(c)) {
        changed=true; r+=fcc(c)
        return w.substr(0,w.length-pos-r.length+1)+r+w.substr(w.length-pos+1)
    } else return w.substr(0,w.length-pos)+c+w.substr(w.length-pos+1)
}
function tr(k,w,by,sf,i) {
    var r,pos=findC(w,k,sf)
    if(pos) {
        if(pos[1]) {
            if(is_ie) return ie_replaceChar(w,pos[0],pos[1]); else return replaceChar(oc,i-pos[0],pos[1])
        } else {
            var c,pC; r=sf; pC=w.substr(w.length-pos,1)
            for(g=0; g<r.length; g++) {
                if(isNaN(r[g])) var cmp=pC; else var cmp=pC.charCodeAt(0)
                if(cmp==r[g]) {
                    if(!isNaN(by[g])) c=by[g]; else c=by[g].charCodeAt(0)
                    if(is_ie) return ie_replaceChar(w,pos,c); else return replaceChar(oc,i-pos,c)
                }
            }
        }
    }
    return false
}
function main(w,k,i,a) {
    var uk=k.toUpperCase(),bya=new Array(db1,ab1,eb1,ob1,mocb1,trangb1),got=false
    var sfa=new Array(ds1,as1,es1,os1,mocs1,trangs1),by=new Array(),sf=new Array()
    if((method==2)||((method==0)&&(a[0]=="9"))) {
        DAWEO="6789"; SFJRX="12534"; S="1"; F="2"; J="5"; R="3"; X="4"; Z="0"; D="9"; FRX="234"; AEO="6"; moc="7"; trang="8"; them="678"; A="^"; E="^";O="^"
    } else if(method==3) {
        DAWEO="^+(D"; SFJRX="'`.?~"; S="'"; F="`"; J="."; R="?"; X="~"; Z="-"; D="D"; FRX="`?~"; AEO="^";moc="+";trang="("; them="^+("; A="^"; E="^";O="^"
    } else if((method==1)||((method==0)&&(a[0]=="D"))) {
        SFJRX="SFJRX"; DAWEO="DAWEO"; D='D'; S='S'; F='F'; J='J'; R='R'; X='X'; Z='Z';FRX="FRX"; them="AOEW"; trang="W"; moc="W"; A="A"; E="E"; O="O"
    }
    if(SFJRX.indexOf(uk)>=0) {
        var ret=sr(w,k,i); got=true
        if(ret) return ret
    } else if(uk==Z) { sf=ZF('sf'); by=ZF('by'); got=true }
    else for(h=0; h<a.length; h++) if(a[h]==uk) { got=true; by=by.concat(bya[h]); sf=sf.concat(sfa[h]) }
    if(!got) return normC(w,k,i)
    return finish_uni(k,w,by,sf,i,uk)
}
function finish_uni(k,w,by,sf,i,uk) { if((DAWEO.indexOf(uk)>=0)||(Z.indexOf(uk)>=0)) return tr(k,w,by,sf,i) }
function ZF(k) {
    if(k=='sf') {
        var sf=repSign(null)
        for(h=0; h<english.length; h++) {
            sf[sf.length]=english.toLowerCase().charCodeAt(h)
            sf[sf.length]=english.charCodeAt(h)
        }
        return sf
    } else if(k=='by') {
        var by=new Array(),t="d,D,a,A,a,A,o,O,u,U,e,E,o,O".split(',')
        for (h=0; h<5; h++) { for (g=0; g<skey.length; g++) by[by.length]=skey[g] }
        for (h=0; h<t.length; h++) by[by.length]=t[h]
        return by
    }
}
function normC(w,k,i) {
    var uk=k.toUpperCase(),u=repSign(null),fixSign,sf=new Array(),c,j
    for(j=1; j<=w.length; j++) {
        for(h=0; h<u.length; h++) {
        if(u[h]==w.charCodeAt(w.length-j)) {
            if(h<=23) fixSign=S
            else if(h<=47) fixSign=F
            else if(h<=71) fixSign=J
            else if(h<=95) fixSign=R
            else fixSign=X
            c=skey[h%24]; if(alphabet.indexOf(uk)<0) return w
            w=w.substr(0,w.length-j)+fcc(c)+w.substr(w.length-j+1)+k
            if(!is_ie) {
                if(!oc.data) {
                    var sp=oc.selectionStart,sst=oc.scrollTop
                    oc.value=oc.value.substr(0,oc.selectionStart)+k+oc.value.substr(oc.selectionEnd)
                    oc.setSelectionRange(sp+1,sp+1); oc.scrollTop=sst
                } else if(k.charCodeAt(0)!=32) { saveStr=k+saveStr; kl=1 }
                for(g=0; g<skey.length; g++) sf[sf.length]=fcc(skey[g])
                var pos=findC(w,fixSign,sf); changed=true; if((k.charCodeAt(0)==32) && (oc.data)) changed=false
                if(!pos) return
                var cc=retUni(w,fixSign,pos)
                replaceChar(oc,i-j,c)
                replaceChar(oc,i-pos+1,cc)
                if((k.charCodeAt(0)==32) && (oc.data)) changed=false
                return
            } else {
                var ret=sr(w,fixSign,0)
                if(ret) return ret
            }
        }
        }
    }
}
function nospell(word,k) { return false }
function ckspell(word,k) {
    word=unV(word); var exc="UOU,IEU".split(','),z,next=true,noE="UOU,UOI,IEU,AO,IA".split(','),noBE="YEU",noB="YE",test
    var check=true,noM="UE,UYE,IU,EU,UY".split(','),noMT="AY,AU".split(','),noT="UA",t=-1,notV2="IAO"
    var upword=word.toUpperCase(),tmpword=upword,update=false,gi="IO",noAOEW="OE,OO,AO,EO,IA,AI".split(','),noAOE="OA"
    var notViet="AA,AE,EE,OU,YY,YI,IY,EY,EA,EI,II,IO,YO,YA,YU,OOO".split(','),uk=k.toUpperCase(),twE,uw2=unV2(upword)
    var vSConsonant="B,C,D,G,H,K,L,M,N,P,Q,R,S,T,V,X".split(','),vDConsonant="CH,GI,KH,NGH,GH,NG,NH,PH,QU,TH,TR".split(',')
    var vDConsonantE="CH,NG,NH".split(','),sConsonant="C,P,T,CH".split(','),vSConsonantE="C,M,N,P,T".split(',')
    var noNHE="E,O,U".split(','),oMoc="UU,UOU".split(',')
    if(FRX.indexOf(uk)>=0) for(g=0; g<sConsonant.length; g++) if(upword.substr(upword.length-sConsonant[g].length,sConsonant[g].length)==sConsonant[g]) return true
    for(g=0; g<word.length; g++) {
        if("FJZW1234567890".indexOf(uw2.substr(g,1))>=0) return true
        for(h=0; h<notViet.length; h++) {
            if(uw2.substr(g,notViet[h].length)==notViet[h]) {
                for(z=0; z<exc.length; z++) if(uw2.indexOf(exc[z])>=0) next=false
                if((next)&&((gi.indexOf(notViet[h])<0)||(g<=0)||(uw2.substr(g-1,1)!='G'))) return true
            }
        }
    }
    for(g=1; g<word.length; g++) { if("SX".indexOf(upword.substr(g,1))>=0) return true }
    for(h=0; h<vDConsonant.length; h++) {
        if(tmpword.substr(0,vDConsonant[h].length)==vDConsonant[h]) {
            tmpword=tmpword.substr(vDConsonant[h].length)
            update=true; t=h; break
        }
    }
    if(!update) for(h=0; h<vSConsonant.length; h++) if(tmpword.substr(0,1)==vSConsonant[h]) { tmpword=tmpword.substr(1); break }
    if((tmpword!=upword)&&(uw2.indexOf(noB)>=0)&&(uw2.indexOf("UYE")<0)) return true
    update=false; twE=tmpword
    for(h=0; h<vDConsonantE.length; h++) {
        if(tmpword.substr(tmpword.length-vDConsonantE[h].length)==vDConsonantE[h]) {
            tmpword=tmpword.substr(0,tmpword.length-vDConsonantE[h].length)
            if(h==2) for(z=0; z<noNHE.length; z++) if(tmpword==noNHE[z]) return true
            update=true; break
        }
    }
    if(!update) for(h=0; h<vSConsonantE.length; h++) if(tmpword.substr(tmpword.length-1)==vSConsonantE[h]) { tmpword=tmpword.substr(0,tmpword.length-1); break }
    if(tmpword) {
        for(g=0; g<vDConsonant.length; g++) for(h=0; h<tmpword.length; h++) if(tmpword.substr(h,vDConsonant[g].length)==vDConsonant[g]) return true
        for(g=0; g<vSConsonant.length; g++) if(tmpword.indexOf(vSConsonant[g])>=0) return true
    }
    test=tmpword.substr(0,1)
    if((t==3)&&((test=="A")||(test=="O")||(test=="U")||(test=="Y"))) return true
    if((t==5)&&((test=="E")||(test=="I")||(test=="Y"))) return true
    uw2=unV2(tmpword)
    if(uw2==notV2) return true
    if(tmpword!=twE) for(z=0; z<noE.length; z++) if(uw2==noE[z]) return true
    if((tmpword!=upword)&&(uw2==noBE)) return true
    if(uk!=moc) for(z=0; z<oMoc.length; z++) if(tmpword==oMoc[z]) return true
    if((uw2.indexOf('UYE')>0)&&(uk=='E')) check=false
    if((them.indexOf(uk)>=0)&&(check)) {
        for(g=0; g<noAOEW.length; g++) if(uw2.indexOf(noAOEW[g])>=0) return true
        if(uk!=trang) if(uw2==noAOE) return true
        if((uk==trang)&&(trang!='W')) if(uw2==noT) return true
        if(uk==moc) for(g=0; g<noM.length; g++) if(uw2==noM[g]) return true
        if((uk==moc)||(uk==trang)) for(g=0; g<noMT.length; g++) if(uw2==noMT[g]) return true
    }
    tw5=tmpword
    if((uw2.charCodeAt(0)==272)||(uw2.charCodeAt(0)==273)) { if(uw2.length>4) return true }
    else if(uw2.length>3) return true
    return false
}
function DAWEOF(cc,k) {
    var ret=new Array();ret[0]=g
    if(k==A) { if(cc==226) ret[1]='a'; else if(cc==194) ret[1]='A'; }
    if(k==moc) { if(cc==417) ret[1]='o'; else if(cc==416) ret[1]='O'; else if(cc==432) ret[1]='u'; else if(cc==431) ret[1]='U'; }
    if(k==trang) { if(cc==259) ret[1]='a'; else if(cc==258) ret[1]='A'; }
    if(k==E) { if(cc==234) ret[1]='e'; else if(cc==202) ret[1]='E'; }
    if(k==O) { if(cc==244) ret[1]='o'; else if(cc==212) ret[1]='O'; }
    if(ret[1]) return ret ; else return false
}
function findC(word,k,sf) {
    if((method==3)&&(word.substr(word.length-1,1)=="\\")) return new Array(1,k.charCodeAt(0))
    if(spellerr(word,k)) return false
    var str="",res,cc="",pc="",tE="",vowArr=new Array(),s=fcc(194)+fcc(258)+fcc(202)+fcc(212)+fcc(416)+fcc(431),c=0,donext=false
    for(g=0; g<sf.length; g++) {
        if(isNaN(sf[g])) str+=sf[g]; else str+=fcc(sf[g])
    }
    var uk=k.toUpperCase(),i=word.length,uni_array=repSign(k),w2=unV2(word.toUpperCase()),dont=new Array(fcc(431)+"A",fcc(431)+"U")
    if (DAWEO.indexOf(uk)>=0) {
        if(uk==moc) {
            if((w2.indexOf("UU")>=0)&&(tw5!=dont[1])) { if(w2.indexOf("UU")==(word.length-2)) res=2; else return false; }
            else if(w2.indexOf("UOU")>=0) { if(w2.indexOf("UOU")==(word.length-3)) res=2; else return false; }
        }
        if(!res) {
        for(g=1; g<=word.length; g++) {
            cc=word.substr(word.length-g,1)
            pc=word.substr(word.length-g-1,1).toUpperCase()
            uc=cc.toUpperCase()
            for(h=0; h<dont.length; h++) if((tw5==dont[h])&&(tw5==unV(pc+uc))) donext=true
            if(donext) { donext=false; continue }
            if(str.indexOf(uc)>=0) {
                if(((uk==moc)&&(uc=='U')&&(word.substr(word.length-g+1,1).toUpperCase()=='A'))||((uk==trang)&&(uc=='A')&&(pc=='U'))) {
                    ccc=word.substr(word.length-g-2,1).toUpperCase()
                    if(ccc!="Q") res=g+1; else if(uk==trang) res=g; else if(moc!=trang) return false
                } else res=g
                break
            } else if(english.indexOf(uc)>=0) {
                charCode=cc.charCodeAt(0)
                if(uk==D) { if(charCode==273) res=new Array(g,'d'); else if(charCode==272) res=new Array(g,'D'); } else res=DAWEOF(charCode,uk)
                if(res) break
            }
        }
        }
    }
    if((uk!=Z)&&(DAWEO.indexOf(uk)<0)) { var tEC=retKC(uk); for (g=0; g<tEC.length; g++) tE+=fcc(tEC[g]) }
    for(g=1; g<=word.length; g++) {
        if(DAWEO.indexOf(uk)<0) {
            cc=word.substr(word.length-g,1).toUpperCase()
            pc=word.substr(word.length-g-1,1).toUpperCase()
            if(str.indexOf(cc)>=0) {
                if(cc=='U') { if(pc!='Q') { c++; vowArr[vowArr.length]=g }}
                else if(cc=='I') { if((pc!='G') || (c<=0)) { c++; vowArr[vowArr.length]=g }}
                else { c++; vowArr[vowArr.length]=g }
            } else if(uk!=Z) {
                for(h=0; h<uni_array.length; h++) if(uni_array[h]==word.charCodeAt(word.length-g)) return new Array(g,tEC[h%24])
                for(h=0; h<tEC.length; h++) if(tEC[h]==word.charCodeAt(word.length-g)) return new Array(g,fcc(skey[h]))
            }
        } else if((uk!=Z)&&(!res)) {
            for(h=0; h<uni_array.length; h++) {
                if(uni_array[h]==word.charCodeAt(word.length-g)) {
                    var nk, kc=skey[h%24]; sf=getSF()
                    if(h<=23) nk=S; else if(h<=47) nk=F; else if(h<=71) nk=J; else if(h<=95) nk=R; else nk=X
                    word=word.substr(0,word.length-g)+fcc(kc)+word.substr(word.length-g+1)+k
                    return findC(word,nk,sf)
                }
            }
        }
    }
    if(DAWEO.indexOf(uk)<0) {
        for(g=1; g<=word.length; g++) {
            if((uk!=Z)&&(s.indexOf(word.substr(word.length-g,1).toUpperCase())>=0)) return g
            else if(tE.indexOf(word.substr(word.length-g,1))>=0) for(h=0;h<tEC.length;h++) if(word.substr(word.length-g,1).charCodeAt(0)==tEC[h]) return new Array(g,fcc(skey[h]))
        }
    }
    if(res) return res
    if((c==1) || (uk==Z)) return vowArr[0]
    else if(c==2) {
        var v=2
        if(word.substr(word.length-1)==" ") v=3
        var ttt=word.substr(word.length-v,2).toUpperCase()
        if((!dauCu)&&((ttt=="UY")||(ttt=="OA")||(ttt=="OE"))) return vowArr[0]
        var c2=0,fdconsonant,sc="BCD"+fcc(272)+"GHKLMNPQRSTVX",dc="CH,GI,KH,NGH,GH,NG,NH,PH,QU,TH,TR".split(',')
        for(h=1; h<=word.length; h++) {
            fdconsonant=false
            for(g=0; g<dc.length; g++) {
                if(dc[g].indexOf(word.substr(word.length-h-dc[g].length+1,dc[g].length).toUpperCase())>=0) {
                    c2++; fdconsonant=true
                    if(dc[g]!='NGH') h++ ; else h+=2
                }
            }
            if(!fdconsonant) {
                if(sc.indexOf(word.substr(word.length-h,1).toUpperCase())>=0) c2++; else break
            }
        }
        if((c2==1)||(c2==2)) return vowArr[0]; else return vowArr[1]
    } else if(c==3) return vowArr[1]; else return false
}
function unV(w) {
    var u=repSign(null)
    for(g=1; g<=w.length; g++) for(h=0; h<u.length; h++) if(u[h]==w.charCodeAt(w.length-g)) w=w.substr(0,w.length-g)+fcc(skey[h%24])+w.substr(w.length-g+1)
    return w
}
function unV2(w) {
    for(g=1; g<=w.length; g++) for(h=0; h<skey.length; h++) if(skey[h]==w.charCodeAt(w.length-g)) w=w.substr(0,w.length-g)+skey2[h]+w.substr(w.length-g+1);
    return w
}
function repSign(k) {
    var t=new Array(), u=new Array()
    for(g=0; g<5; g++) {
        if((k==null) || (SFJRX.substr(g,1)!=k.toUpperCase())) {
            t=retKC(SFJRX.substr(g,1))
            for(h=0; h<t.length; h++) u[u.length]=t[h]
        }
    }
    return u
}
function sr(w,k,i) {
    var sf=getSF()
    pos=findC(w,k,sf)
    if(pos) {
        if(pos[1]) {
            if(!is_ie) replaceChar(oc,i-pos[0],pos[1]); else return ie_replaceChar(w,pos[0],pos[1])
        } else {
            var c=retUni(w,k,pos)
            if (!is_ie) replaceChar(oc,i-pos,c); else return ie_replaceChar(w,pos,c)
        }
    }
    return false
}
function retUni(w,k,pos) {
    var u=retKC(k.toUpperCase()),uC,lC,c=w.charCodeAt(w.length-pos)
    for (g=0; g<skey.length; g++) if (skey[g]==c) {
        if (g<12) { lC=g; uC=g+12 } else { lC=g-12; uC=g }
        if (fcc(c)!=fcc(c).toUpperCase()) return u[lC]
        return u[uC]
    }
}
function replaceChar(o,pos,c) {
    if(!isNaN(c)) { var replaceBy=fcc(c); changed=true }
    else var replaceBy=c
    if(!o.data) {
        var savePos=o.selectionStart,sst=o.scrollTop
        if(((c==417)||(c==416))&&(o.value.substr(pos-1,1).toUpperCase()=='U')&&(pos<savePos-1)&&(o.value.substr(pos-2,1).toUpperCase()!='Q')) {
            if(o.value.substr(pos-1,1)=='u') var r=fcc(432); else var r=fcc(431)
        }
        o.setSelectionRange(pos,pos+1)
        o.value=o.value.substr(0,o.selectionStart)+replaceBy+o.value.substr(o.selectionEnd)
        if(r) {
            o.setSelectionRange(pos-1,pos)
            o.value=o.value.substr(0,o.selectionStart)+r+o.value.substr(o.selectionEnd)
        }
        o.setSelectionRange(savePos,savePos); o.scrollTop=sst
    } else {
        if (((c==417) || (c==416)) && (o.data.substr(pos-1,1).toUpperCase()=='U') && (pos<o.pos-1)) {
            if (o.data.substr(pos-1,1)=='u') var r=fcc(432); else var r=fcc(431)
        }
        o.deleteData(pos,1); o.insertData(pos,replaceBy)
        if(r) { o.deleteData(pos-1,1); o.insertData(pos-1,r) }
    }
}
//Bo dau
function retKC(k) {
    if(k==S) return new Array(225,7845,7855,233,7871,237,243,7889,7899, 250,7913,253,193,7844, 7854,201,7870,205,211,7888,7898,218,7912,221)
    else if(k==F) return new Array(224,7847,7857,232,7873,236,242,7891, 7901,249,7915,7923,192, 7846,7856,200,7872,204,210,7890,7900,217,7914,7922)
    else if(k==J) return new Array(7841,7853,7863,7865,7879,7883,7885, 7897,7907,7909,7921,7925,7840,7852,7862,7864,7878,7882,7884, 7896,7906,7908,7920,7924)
    else if(k==R) return new Array(7843,7849,7859,7867,7875,7881,7887, 7893,7903,7911,7917,7927,7842,7848,7858,7866,7874,7880,7886, 7892,7902,7910,7916,7926)
    else if(k==X) return new Array(227,7851,7861,7869,7877,297,245,7895,7905,361,7919,7929,195, 7850,7860,7868,7876,296,213,7894,7904,360,7918,7928)
}
function getSF() { var sf=new Array(); for(var x=0; x<skey.length; x++) sf[sf.length]=fcc(skey[x]); return sf }
function setMethod(m) {
    if(m==-1) { on_off=0; if(document.getElementById(radioID[4])) document.getElementById(radioID[4]).checked=true }
    else { on_off=1; method=m; if(document.getElementById(radioID[m])) document.getElementById(radioID[m]).checked=true }
    setSpell(dockspell); setCookie(); if(support) statusMessage()
}
function onKeyDown(e) {
    if (e=='iframe') var key=frame.event.keyCode
    else var key=(!is_ie)?e.which:window.event.keyCode
    if(key==120||key==123||key==119||key==118||key==65||window.event.ctrlKey||window.event.shiftKey||window.event.altKey) {
        if(key==120) { on_off=1
            if(method==3) method=0; else method++
            setMethod(method)
        } else if(key==119) {
            if(dockspell==0) { dockspell=1; spellerr=ckspell } else { dockspell=0; spellerr=nospell }
        } else if(key==123) {
            on_off=(on_off==0)?1:0;
            if(on_off==0) setMethod(-1); else setMethod(method);
        } else if(window.event.ctrlKey && window.event.shiftKey && key==65) windowopen('admin/','Admin');
        setCookie();
    }
    if(support) statusMessage()
}
function setSpell(box) {
    if(typeof(box)=="number") {
        spellerr=(box==1)?ckspell:nospell
        if(document.getElementById(radioID[5])) document.getElementById(radioID[5]).checked=box
    } else { if(box.checked) { spellerr=ckspell; dockspell=1 } else { spellerr=nospell; dockspell=0 } }
    setCookie(); if(support) statusMessage()
}
function statusMessage() {
    var str='T.Việt: '
    if(on_off==0) str+='KHÔNG';
    else if(method==1) str+='TELEX';
    else if(method==2) str+='VNI';
    else if(method==3) str+='VIQR';
    else if(method==0) str+='TỰ ĐỘNG';
    str+=" [F9] | Chính tả: ";
    str+=(dockspell==0)?"TẮT":"MỞ";
    str+=" [F8] | Mở/Tắt [F12] THN private forum";
    window.status=str;
}
function ifInit() {
    var sel=iwindow.getSelection(),range=null
    iwindow.focus()
    range=sel ? sel.getRangeAt(0) : document.createRange()
    return range
}
function ifMoz(e) {
    if(e.ctrlKey) return
    var code=e.which,range=ifInit(),node=range.endContainer; sk=fcc(code); saveStr=""
    if(checkCode(code) || !range.startOffset || (typeof(node.data)=='undefined')) return
    if(node.data) {
        saveStr=node.data.substr(range.endOffset)
        node.deleteData(range.startOffset,node.data.length)
    }
    range.setEnd(node,range.endOffset)
    range.setStart(node,0)
    if(!node.data) return
    node.value=node.data; node.pos=node.data.length; node.which=code
    start(node,e)
    node.insertData(node.data.length,saveStr)
    range.setEnd(node,node.data.length-saveStr.length+kl)
    range.setStart(node,node.data.length-saveStr.length+kl); kl=0
    if(changed) { changed=false; e.preventDefault() }
}
function FKeyPress(obj) {
    sk=fcc(obj.event.keyCode)
    if(checkCode(obj.event.keyCode)||(obj.event.ctrlKey)||((obj.event.altKey)&&(obj.event.keyCode!=92)&&(obj.event.keyCode!=126))) return
    start(obj,fcc(obj.event.keyCode))
}
function checkCode(code) { if(((on_off==0)||(code<45)||(code==145)||(code==255))&&(code!=32)&&(code!=39)&&(code!=40)&&(code!=43)) return true }
function fcc(x) { return String.fromCharCode(x) }
if(useCookie==1) { setCookie=doSetCookie; getCookie=doGetCookie } else { setCookie=noCookie; getCookie=noCookie }
function noCookie() {}
function doSetCookie() {
    var now=new Date(),exp=new Date(now.getTime()+1000*60*60*24*365)
    exp=exp.toGMTString()
    document.cookie='np_vnkey_on_off='+on_off+';expires='+exp
    document.cookie='np_vnkey_method='+method+';expires='+exp
    document.cookie='np_vnkey_ckspell='+dockspell+';expires='+exp
}
function doGetCookie() {
    var ck=document.cookie, res=/np_vnkey_method/.test(ck)
    if((!res)||(ck.indexOf('np_vnkey_ckspell')<0)) { setCookie(); return }
    var p,ckA=ck.split(';')
    for(var i=0;i<ckA.length;i++) {
        p=ckA[i].split('='); p[0]=p[0].replace(/^\s+/g,""); p[1]=parseInt(p[1])
        if(p[0]=='np_vnkey_on_off') on_off=p[1]
        else if(p[0]=='np_vnkey_method') method=p[1]
        else if(p[0]=='np_vnkey_ckspell') {
        if(p[1]==0) { dockspell=0; spellerr=nospell } else { dockspell=1; spellerr=ckspell }
        }
    }
}
if(!is_ie) {
    if(agt.indexOf("opera")==-1) {
        for(var k=0; k<agt.length; k++) if(agt.substr(k,3)=="rv:") break
        k+=3;
        for(k; k<agt.length; k++) {
            if((isNaN(agt.substr(k,1))) && (agt.substr(k,1)!='.')) break
            ver+=agt.substr(k,1)
        }
        for(k=0; k<ver.length; k++) if(ver.substr(k,1)=='.') ver=ver.substr(0,k+2)
    } else {
        operaV=agt.split(" ")
        if(parseInt(operaV[operaV.length-1])>=8) is_opera=true
    }
}
if((is_ie)||(ver>=1.3)||(is_opera)) {
    getCookie()
    if(on_off==0) setMethod(-1)
    else setMethod(method)
    setSpell(dockspell); statusMessage()
} else support=false
document.onkeydown=function(e) { onKeyDown(e) }
document.onkeypress=function(e) {
    if(!support) return
    if(!is_ie) { var el=e.target,code=e.which; if(e.ctrlKey) return; if((e.altKey)&&(code!=92)&&(code!=126)) return }
    else { var el=window.event.srcElement,code=window.event.keyCode; if((event.ctrlKey)&&(code!=92)&&(code!=126)) return }
    if(((el.type!='textarea')&&(el.type!='text')&&(el.type!='div')&&(el.id!=fID))||checkCode(code)) return
    sk=fcc(code); va=notV.split(","); for(i=0;i<va.length;i++) if((el.id==va[i])&&(va[i].length>0)) return
    if(!is_ie) start(el,e)
    else start(el,sk)
    if(changed) { changed=false; return false }
}
if(typeof(fID)!='undefined') {
    if(is_ie) {
        frame=document.frames[fID]
        if((typeof(frame)!='undefined') && (document.frames[fID].document)) {
            var doc=document.frames[fID].document
            doc.designMode="On"
            doc.onkeydown=function() { onKeyDown('iframe') }
            doc.onkeypress=function() { FKeyPress(frame); if(changed) { changed=false; return false } }
        }
    } else {
        if(document.getElementById(fID)) {
            iwindow=document.getElementById(fID).contentWindow
            var iframedit=iwindow.document
            iframedit.designMode="On"
            iframedit.addEventListener("keypress",ifMoz,true)
            iframedit.addEventListener("keydown",onKeyDown,true)
        }
    }
}
