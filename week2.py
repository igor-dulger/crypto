import sys
import numpy

key = '140b41b22a29beb4061bda66b6747e14'
key_bin = bytearray.fromhex(key)
cipher_text = '4ca00ff4c898d61e1edbf1800618fb2828a226d160dad07883d04e008a7897ee2e4b7465d5290d0c0e6c6822236e1daafb94ffe0c5da05d9476be028ad7c1d81'
cipher_text_bin = bytearray.fromhex(cipher_text)

print len(key_bin), key_bin

cipher_blocks = numpy.reshape(cipher_text_bin, -1, 16)

print "\n"
blocks = len(cipher_text_bin)/16

for i in range(blocks):
    c = blocks - i
    for j in range((c-1)*16, c*16):
        print i, c, j
    print "\n"



